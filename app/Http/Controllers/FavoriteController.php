<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /** Tampilkan halaman favorit */
    public function index()
    {
        $favorites = Favorite::with('product.category')
            ->where('user_id', Auth::id())
            ->latest('created_at')
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    /** Toggle favorit (tambah / hapus) */
    public function toggle(Request $request, $productId)
    {
        Product::findOrFail($productId);

        $fav = Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($fav) {
            $fav->delete();
            $isFavorited = false;
            $msg = 'Produk dihapus dari favorit.';
        } else {
            Favorite::create([
                'user_id'    => Auth::id(),
                'product_id' => $productId,
            ]);
            $isFavorited = true;
            $msg = 'Produk ditambahkan ke favorit.';
        }

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'is_favorited' => $isFavorited,
                'message'      => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }
}
