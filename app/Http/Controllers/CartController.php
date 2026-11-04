<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /** Tampilkan halaman keranjang */
    public function index()
    {
        $carts = Cart::with('product.category')
            ->where('user_id', Auth::id())
            ->get();

        $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);

        return view('cart.index', compact('carts', 'total'));
    }

    /** Tambah produk ke keranjang */
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->stock <= 0) {
            return back()->with('error', 'Produk ini sedang habis.');
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $productId],
            ['quantity' => 0]
        );

        if ($cart->quantity + 1 > $product->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        $cart->increment('quantity');
        $cart->save();

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    /** Update kuantitas */
    public function update(Request $request, $cartId)
    {
        $cart = Cart::where('id', $cartId)->where('user_id', Auth::id())->firstOrFail();

        $qty = max(1, (int) $request->input('quantity', 1));
        if ($qty > $cart->product->stock) {
            $qty = $cart->product->stock;
        }

        $cart->update(['quantity' => $qty]);

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    /** Hapus dari keranjang */
    public function destroy($cartId)
    {
        Cart::where('id', $cartId)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /** Checkout via WhatsApp */
    public function checkout()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        $message = "Halo Juragan Batik, saya ingin memesan:\n\n";
        $total = 0;

        foreach ($carts as $cart) {
            $subtotal = $cart->product->price * $cart->quantity;
            $total += $subtotal;
            $message .= "• {$cart->product->name} ({$cart->quantity}x) - Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }

        $message .= "\nTotal: Rp " . number_format($total, 0, ',', '.');
        $message .= "\n\nAtas nama: " . Auth::user()->name;

        // Kosongkan keranjang
        Cart::where('user_id', Auth::id())->delete();

        return redirect("https://wa.me/6281382958012?text=" . urlencode($message));
    }
}
