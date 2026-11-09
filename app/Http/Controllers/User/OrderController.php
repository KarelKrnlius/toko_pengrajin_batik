<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Tampilkan riwayat pesanan milik user yang sedang aktif.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $query = Order::where('user_id', $userId)
            ->with(['items.product.category']);

        // Filter status pesanan (pending, shipped, ready_pickup, completed)
        if ($request->filled('status')) {
            if ($request->status === 'ready_pickup') {
                $query->where('status', 'shipped')->where('shipping_courier', 'Ambil Mandiri');
            } elseif ($request->status === 'shipped') {
                $query->where('status', 'shipped')->where('shipping_courier', '!=', 'Ambil Mandiri');
            } elseif (in_array($request->status, ['pending', 'completed'])) {
                $query->where('status', $request->status);
            }
        }

        // Pencarian hanya berdasarkan kode nomor pesanan
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('order_number', 'ilike', "%{$search}%");
        }

        // Sorting: Terlama / Terbaru (Default: Terbaru)
        if ($request->sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $orders = $query->paginate(10)->withQueryString();

        // Hitung jumlah masing-masing status untuk tab pesanan user
        $counts = [
            'all'          => Order::where('user_id', $userId)->count(),
            'pending'      => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'shipped'      => Order::where('user_id', $userId)->where('status', 'shipped')->where('shipping_courier', '!=', 'Ambil Mandiri')->count(),
            'ready_pickup' => Order::where('user_id', $userId)->where('status', 'shipped')->where('shipping_courier', 'Ambil Mandiri')->count(),
            'completed'    => Order::where('user_id', $userId)->where('status', 'completed')->count(),
        ];

        return view('user.orders.index', compact('orders', 'counts'));
    }

    /**
     * Konfirmasi pesanan telah diterima / sampai oleh pelanggan.
     * Mengubah status dari 'shipped' menjadi 'completed'.
     */
    public function completeOrder(Request $request, int $id): RedirectResponse
    {
        // Pastikan pesanan benar-benar milik user yang sedang login
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'shipped') {
            return back()->with('error', 'Hanya pesanan yang sedang dalam perjalanan (shipped) yang dapat dikonfirmasi sampai.');
        }

        $order->update([
            'status' => 'completed',
        ]);

        return back()->with('success', "Alhamdulillah! Terima kasih telah mengonfirmasi penerimaan pesanan {$order->order_number}. Selamat menikmati karya batik adiluhung Surakarta.");
    }
}
