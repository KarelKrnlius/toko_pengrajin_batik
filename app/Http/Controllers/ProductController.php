<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tabel inventaris produk admin — search, sort, paginate 10.
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Product::with('category');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhereHas('category', fn($c) => $c->where('name', 'ilike', '%' . $request->search . '%'));
            });
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sort
        $sort      = $request->get('sort', 'terbaru');
        $sortLabel = match ($sort) {
            'nama_az'    => 'Nama A–Z',
            'nama_za'    => 'Nama Z–A',
            'harga_naik' => 'Harga Terendah',
            'harga_turun'=> 'Harga Tertinggi',
            'stok_naik'  => 'Stok Sedikit',
            'stok_turun' => 'Stok Terbanyak',
            default      => 'Terbaru',
        };

        $query = match ($sort) {
            'nama_az'    => $query->orderBy('name'),
            'nama_za'    => $query->orderByDesc('name'),
            'harga_naik' => $query->orderBy('price'),
            'harga_turun'=> $query->orderByDesc('price'),
            'stok_naik'  => $query->orderBy('stock'),
            'stok_turun' => $query->orderByDesc('stock'),
            default      => $query->latest(),
        };

        $products = $query->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products', 'categories', 'sort', 'sortLabel'));
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Perbarui data produk yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk beserta file gambarnya.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
