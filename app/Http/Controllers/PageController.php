<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Beranda: 3 slot kartu yang auto-rotate dari semua produk.
     */
    public function home()
    {
        $allProducts = Product::with('category')->latest()->get()
            ->map(function ($p) {
                return [
                    'id'       => $p->id,
                    'name'     => $p->name,
                    'category' => $p->category->name ?? 'Batik',
                    'price'    => number_format($p->price, 0, ',', '.'),
                    'desc'     => \Illuminate\Support\Str::limit($p->description, 90),
                    'image'    => $p->image ? \Illuminate\Support\Facades\Storage::url($p->image) : null,
                    'url'      => route('product.show', $p->id),
                    'wa'       => 'https://wa.me/6281234567890?text=' . urlencode('Halo, saya ingin memesan: ' . $p->name),
                ];
            });

        return view('home', compact('allProducts'));
    }

    /**
     * Katalog: cari & filter produk dengan pagination.
     */
    public function catalog(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(6)->withQueryString();

        return view('catalog', compact('products', 'categories'));
    }

    /**
     * Detail produk beserta rekomendasi produk sejenis.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('detail', compact('product', 'related'));
    }

    /**
     * Halaman Cerita Kami.
     */
    public function about()
    {
        return view('about');
    }
}
