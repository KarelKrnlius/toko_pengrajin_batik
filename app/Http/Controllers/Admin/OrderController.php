<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar pesanan dengan filter, pencarian, dan pagination.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items.product'])->latest();

        // Filter status
        if ($request->filled('status')) {
            if ($request->status === 'ready_pickup') {
                $query->where('status', 'shipped')->where('shipping_courier', 'Ambil Mandiri');
            } elseif ($request->status === 'shipped') {
                $query->where('status', 'shipped')->where('shipping_courier', '!=', 'Ambil Mandiri');
            } elseif (in_array($request->status, ['pending', 'completed'])) {
                $query->where('status', $request->status);
            }
        }

        // Filter kurir
        if ($request->filled('courier')) {
            $courier = $request->courier;
            if ($courier === 'JNE') {
                $query->whereIn('shipping_courier', ['JNE', 'JNE Express']);
            } elseif ($courier === 'J&T Express') {
                $query->whereIn('shipping_courier', ['J&T Express', 'J&T']);
            } else {
                $query->where('shipping_courier', $courier);
            }
        }

        // Pencarian (Nomor Pesanan, Nama Pelanggan, Resi, atau Telepon)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'ilike', "%{$search}%")
                    ->orWhere('tracking_number', 'ilike', "%{$search}%")
                    ->orWhere('customer_phone', 'ilike', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    });
            });
        }

        // Hitung ringkasan status pesanan
        $counts = [
            'all'          => Order::count(),
            'pending'      => Order::where('status', 'pending')->count(),
            'shipped'      => Order::where('status', 'shipped')->where('shipping_courier', '!=', 'Ambil Mandiri')->count(),
            'ready_pickup' => Order::where('status', 'shipped')->where('shipping_courier', 'Ambil Mandiri')->count(),
            'completed'    => Order::where('status', 'completed')->count(),
        ];

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    /**
     * Form pembuatan pesanan manual oleh Admin.
     */
    public function create(): View
    {
        // Ambil semua pelanggan aktif
        $users = User::where('role', 'user')->orderBy('name')->get(['id', 'name', 'email', 'phone', 'address']);

        // Ambil produk yang memiliki stok > 0
        $products = Product::query()
            ->where('stock', '>', 0)
            ->with('category:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'stock', 'category_id'])
            ->map(fn (Product $product): array => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (int) $product->price,
                'stock' => (int) $product->stock,
                'category' => $product->category?->name ?? '',
            ])
            ->values();

        return view('admin.orders.create', compact('users', 'products'));
    }

    /**
     * Simpan pesanan baru ke database sekaligus kurangi stok produk secara atomik.
     */
    public function store(Request $request): RedirectResponse
    {
        $isAmbilMandiri = $request->shipping_courier === 'Ambil Mandiri';

        $rules = [
            'user_id'            => 'required|exists:users,id',
            'customer_name'      => 'required|string|max:255',
            'customer_phone'     => 'required|string|max:25',
            'customer_address'   => 'required|string|max:1000',
            'payment_method'     => 'required|in:transfer_bank,qris',
            'shipping_courier'   => 'required|in:JNE,J&T Express,Ambil Mandiri,JNE Express,J&T',
            'shipping_cost'      => 'required|numeric|min:0',
            'tracking_number'    => $isAmbilMandiri ? 'nullable|string|max:100' : 'required|string|max:100',
            'status'             => 'required|in:pending,shipped,completed',
            'notes'              => 'nullable|string|max:500',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ];

        $messages = [
            'user_id.required'          => 'Pilih email pelanggan terlebih dahulu.',
            'customer_name.required'    => 'Nama pelanggan wajib diisi.',
            'customer_phone.required'   => 'Nomor telepon pengiriman wajib diisi.',
            'customer_address.required' => 'Alamat pengiriman wajib diisi.',
            'payment_method.required'   => 'Pilih metode pembayaran.',
            'payment_method.in'         => 'Metode pembayaran hanya Transfer Bank atau QRIS.',
            'shipping_courier.required' => 'Pilih ekspedisi kurir.',
            'shipping_courier.in'       => 'Ekspedisi kurir hanya JNE, J&T Express, atau Ambil Mandiri.',
            'tracking_number.required'  => 'Nomor resi wajib diisi untuk pengiriman kurir ekspedisi.',
            'items.required'            => 'Minimal pilih satu produk untuk dipesan.',
            'items.min'                 => 'Minimal pilih satu produk untuk dipesan.',
            'items.*.quantity.min'      => 'Jumlah kuantitas produk minimal 1.',
        ];

        $validated = $request->validate($rules, $messages);

        $order = null;

        DB::transaction(function () use ($validated, $isAmbilMandiri, &$order) {
            // Generate nomor pesanan unik: JB-YYYYMMDD-XXXX (dijamin unik & acak harian)
            $datePart = now()->format('Ymd');
            do {
                $randomPart = strtoupper(Str::random(4));
                $orderNumber = "JB-{$datePart}-{$randomPart}";
            } while (Order::where('order_number', $orderNumber)->exists());

            // Validasi & Kunci stok produk untuk pencegahan race condition
            $totalProductPrice = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Stok untuk produk '{$product->name}' tidak mencukupi (Tersisa: {$product->stock}, diminta: {$item['quantity']}).",
                    ]);
                }

                $totalProductPrice += $product->price * $item['quantity'];
            }

            $shippingCost = $isAmbilMandiri ? 0 : (int) $validated['shipping_cost'];
            $trackingNumber = $isAmbilMandiri ? null : ($validated['tracking_number'] ?? null);
            $totalPrice   = $totalProductPrice + $shippingCost;

            // Simpan Order
            $order = Order::create([
                'order_number'     => $orderNumber,
                'user_id'          => $validated['user_id'],
                'total_price'      => $totalPrice,
                'payment_method'   => $validated['payment_method'],
                'shipping_courier' => $validated['shipping_courier'],
                'shipping_cost'    => $shippingCost,
                'tracking_number'  => $trackingNumber,
                'status'           => $validated['status'],
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            // Simpan setiap OrderItem & kurangi stok produk
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price,
                ]);

                // Pengurangan stok secara otomatis
                $product->decrement('stock', $item['quantity']);
            }

            // Perbarui nama, telepon & alamat pada profil user
            $user = User::find($validated['user_id']);
            if ($user) {
                $user->update([
                    'name'    => $validated['customer_name'],
                    'phone'   => $validated['customer_phone'],
                    'address' => $validated['customer_address'],
                ]);
            }
        });

        return redirect()->route('admin.orders.index')
            ->with('success', "Pesanan {$order->order_number} berhasil dibuat dan stok produk telah diperbarui.");
    }

    /**
     * API/AJAX Endpoint untuk mengambil data user (telepon & alamat) secara real-time.
     */
    public function getUserDetails(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->phone ?? '',
                'address' => $user->address ?? '',
            ],
        ]);
    }

    /**
     * Perbarui status pesanan dan/atau nomor resi pengiriman.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $isAmbilMandiri = $order->shipping_courier === 'Ambil Mandiri';

        $validated = $request->validate([
            'status'          => 'required|in:pending,shipped,completed',
            'tracking_number' => $isAmbilMandiri ? 'nullable' : 'nullable|string|max:100',
        ]);

        if ($isAmbilMandiri) {
            $validated['tracking_number'] = null;
        }

        $order->update($validated);

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diperbarui.");
    }

    /**
     * Hapus pesanan dan kembalikan stok produk secara otomatis.
     */
    public function destroy(Order $order): RedirectResponse
    {
        DB::transaction(function () use ($order) {
            // Kembalikan stok produk jika pesanan dihapus
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->delete();
        });

        return back()->with('success', "Pesanan {$order->order_number} berhasil dihapus dan stok produk telah dikembalikan.");
    }
}
