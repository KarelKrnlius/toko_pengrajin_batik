<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ── Halaman Publik ──────────────────────────────────────────────
Route::get('/',             [PageController::class, 'home'])    ->name('home');
Route::get('/katalog',      [PageController::class, 'catalog']) ->name('catalog');
Route::get('/katalog/{id}', [PageController::class, 'show'])     ->name('product.show');
Route::get('/cerita-kami',  [PageController::class, 'about'])    ->name('about');

// ── Auth ────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])   ->name('login');
    Route::post('/login',   [AuthController::class, 'login'])        ->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])   ->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── User: Keranjang & Favorit (wajib login) ────────────────────
Route::middleware('auth')->group(function () {
    // Keranjang
    Route::get('/keranjang',                  [CartController::class, 'index'])   ->name('cart.index');
    Route::post('/keranjang/{productId}',      [CartController::class, 'store'])   ->name('cart.store');
    Route::put('/keranjang/{cartId}/update',   [CartController::class, 'update'])  ->name('cart.update');
    Route::delete('/keranjang/{cartId}',       [CartController::class, 'destroy']) ->name('cart.destroy');
    Route::post('/keranjang/checkout',        [CartController::class, 'checkout'])->name('cart.checkout');

    // Favorit
    Route::get('/favorit',                     [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorit/{productId}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// ── Admin Panel (wajib login + role admin) ─────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/products',         [ProductController::class, 'index'])  ->name('products.index');
    Route::post('/products',        [ProductController::class, 'store'])  ->name('products.store');
    Route::put('/products/{id}',    [ProductController::class, 'update']) ->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});
