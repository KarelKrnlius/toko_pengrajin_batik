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

        $qtyToAdd = max(1, (int) $request->input('quantity', 1));

        if ($qtyToAdd > $product->stock) {
            return back()->with('error', "Jumlah yang diinginkan ({$qtyToAdd}) melebihi stok yang tersedia (Maksimal: {$product->stock} pcs).");
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $productId],
            ['quantity' => 0]
        );

        if ($cart->quantity + $qtyToAdd > $product->stock) {
            return back()->with('error', "Jumlah total di keranjang melebihi stok yang tersedia (Di keranjang: {$cart->quantity}, Tersedia: {$product->stock} pcs).");
        }

        $cart->quantity += $qtyToAdd;
        $cart->save();

        return back()->with('success', "{$qtyToAdd} produk berhasil ditambahkan ke keranjang.");
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isProfileComplete()) {
            return redirect()->route('profile.show')
                ->with('warning', 'Mohon lengkapi data profil Anda terlebih dahulu (nomor telepon dan alamat pengiriman) sebelum melakukan checkout pesanan.');
        }

        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        // Validasi stok produk sebelum checkout
        foreach ($carts as $cart) {
            $product = $cart->product;
            if (!$product || $product->stock <= 0) {
                return redirect()->route('cart.index')
                    ->with('error', "Produk \"{$product->name}\" saat ini habis. Mohon sesuaikan keranjang belanja Anda terlebih dahulu.");
            }
            if ($cart->quantity > $product->stock) {
                return redirect()->route('cart.index')
                    ->with('error', "Jumlah produk \"{$product->name}\" yang dipesan ({$cart->quantity} pcs) melebihi stok yang tersedia (sisa {$product->stock} pcs). Mohon sesuaikan jumlah sebelum checkout.");
            }
        }

        $message = "Halo Juragan Batik, saya ingin memesan:\n\n";
        $total = 0;

        foreach ($carts as $cart) {
            $subtotal = $cart->product->price * $cart->quantity;
            $total += $subtotal;
            $message .= "• {$cart->product->name} ({$cart->quantity}x) - Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }

        $message .= "\nTotal: Rp " . number_format($total, 0, ',', '.') . "\n\n";
        $message .= "Data Pemesan:\n";
        $message .= "• Nama: " . $user->name . "\n";
        $message .= "• Email: " . $user->email . "\n";
        $message .= "• No. Telepon: " . $user->phone . "\n";
        $message .= "• Alamat Pengiriman: " . $user->address;

        // Kosongkan keranjang
        Cart::where('user_id', $user->id)->delete();

        $whatsappUrl = "https://wa.me/6281382958012?text=" . urlencode($message);

        return redirect()->route('cart.index')
            ->with('checkout_success', true)
            ->with('success', 'Checkout berhasil! Pesanan Anda telah dicatat dan keranjang belanja telah dikosongkan. Menghubungkan ke WhatsApp...')
            ->with('whatsapp_url', $whatsappUrl);
    }

    /** Beli langsung produk via WhatsApp */
    public function buyDirect($productId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isProfileComplete()) {
            return redirect()->route('profile.show')
                ->with('warning', 'Mohon lengkapi data profil Anda terlebih dahulu (nomor telepon dan alamat pengiriman) sebelum melakukan pembelian.');
        }

        $product = Product::with('category')->findOrFail($productId);

        if ($product->stock <= 0) {
            return back()->with('error', 'Produk ini sedang habis.');
        }

        $message = "Halo Juragan Batik, saya ingin memesan produk berikut:\n\n";
        $message .= "• Produk: " . $product->name . "\n";
        if ($product->category) {
            $message .= "• Kategori: " . $product->category->name . "\n";
        }
        $message .= "• Harga: Rp " . number_format($product->price, 0, ',', '.') . "\n";
        $message .= "• Jumlah: 1 pcs\n\n";
        $message .= "Data Pemesan:\n";
        $message .= "• Nama: " . $user->name . "\n";
        $message .= "• Email: " . $user->email . "\n";
        $message .= "• No. Telepon: " . $user->phone . "\n";
        $message .= "• Alamat Pengiriman: " . $user->address;

        $whatsappUrl = "https://wa.me/6281382958012?text=" . urlencode($message);

        return redirect($whatsappUrl);
    }
}
