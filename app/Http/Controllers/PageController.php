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
                    'wa'       => 'https://wa.me/6281382958012?text=' . urlencode('Halo, saya ingin memesan: ' . $p->name),
                ];
            });

        // Gambar hero — ambil produk pertama yang punya gambar
        $heroProduct = Product::with('category')
            ->whereNotNull('image')
            ->latest()
            ->first();

        return view('home', compact('allProducts', 'heroProduct'));
    }

    /**
     * Katalog: cari & filter produk dengan pagination.
     */
    public function catalog(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $query = Product::with('category');

        // Search nama produk
        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter harga min & max
        if ($request->filled('price_min') && (int)$request->price_min > 0) {
            $query->where('price', '>=', (int) $request->price_min);
        }
        if ($request->filled('price_max') && (int)$request->price_max < 10000000) {
            $query->where('price', '<=', (int) $request->price_max);
        }

        // Sort
        $sort = $request->get('sort', 'terbaru');
        $query = match ($sort) {
            'nama_az'    => $query->orderBy('name'),
            'nama_za'    => $query->orderByDesc('name'),
            'harga_naik' => $query->orderBy('price'),
            'harga_turun'=> $query->orderByDesc('price'),
            default      => $query->latest(),
        };

        $products = $query->paginate(6)->withQueryString();

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
