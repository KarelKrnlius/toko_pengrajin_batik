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
            ->with(['items.product.category'])
            ->latest();

        // Filter status pesanan (pending, shipped, completed)
        if ($request->filled('status') && in_array($request->status, ['pending', 'shipped', 'completed'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(6)->withQueryString();

        // Hitung jumlah masing-masing status untuk tab pesanan user
        $counts = [
            'all'       => Order::where('user_id', $userId)->count(),
            'pending'   => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'shipped'   => Order::where('user_id', $userId)->where('status', 'shipped')->count(),
            'completed' => Order::where('user_id', $userId)->where('status', 'completed')->count(),
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

        return back()->with('success', "Alhamdulillah! Terima kasih telah mengonfirmasi penerimaan pesanan #{$order->order_number}. Selamat menikmati karya batik adiluhung Surakarta.");
    }
}
