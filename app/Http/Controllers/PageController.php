<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Beranda: tampilkan 3 produk terbaru.
     */
    public function home()
    {
        $latestProducts = Product::with('category')->latest()->take(3)->get();

        return view('home', compact('latestProducts'));
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
