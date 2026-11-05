<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Ambil daftar kategori (JSON) dengan sort & pagination.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $sort = $request->get('sort', 'terbaru');

        $query = match ($sort) {
            'terlama' => Category::oldest(),
            'a-z'     => Category::orderBy('name'),
            'z-a'     => Category::orderByDesc('name'),
            default   => Category::latest(),
        };

        $categories = $query->paginate(10);

        return response()->json($categories);
    }

    /**
     * Simpan kategori baru.
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message'  => 'Kategori berhasil ditambahkan.',
            'category' => $category,
        ], 201);
    }

    /**
     * Hapus kategori.
     *
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $productCount = $category->products()->count();

        if ($productCount > 0) {
            return response()->json([
                'message' => "Kategori \"{$category->name}\" tidak dapat dihapus karena memiliki {$productCount} produk.",
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
