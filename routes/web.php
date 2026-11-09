<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\OrderController;
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

// ── User: Keranjang, Favorit & Profil (wajib login) ─────────────
Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profil',                      [ProfileController::class, 'show'])          ->name('profile.show');
    Route::put('/profil',                      [ProfileController::class, 'update'])        ->name('profile.update');
    Route::put('/profil/password',             [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Beli Langsung via WhatsApp
    Route::get('/beli/{productId}',            [CartController::class, 'buyDirect'])->name('product.buy')->whereNumber('productId');

    // Keranjang
    Route::get('/keranjang',                  [CartController::class, 'index'])   ->name('cart.index');
    Route::post('/keranjang/checkout',        [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/keranjang/{productId}',      [CartController::class, 'store'])   ->name('cart.store')->whereNumber('productId');
    Route::put('/keranjang/{cartId}/update',   [CartController::class, 'update'])  ->name('cart.update')->whereNumber('cartId');
    Route::delete('/keranjang/{cartId}',       [CartController::class, 'destroy']) ->name('cart.destroy')->whereNumber('cartId');

    // Favorit
    Route::get('/favorit',                     [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorit/{productId}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle')->whereNumber('productId');

    // Pesanan Saya (User Order History)
    Route::get('/pesanan',                     [\App\Http\Controllers\User\OrderController::class, 'index'])        ->name('user.orders.index');
    Route::patch('/pesanan/{id}/terima',       [\App\Http\Controllers\User\OrderController::class, 'completeOrder'])->name('user.orders.complete');
});

// ── Admin Panel (wajib login + role admin) ─────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/products',         [ProductController::class, 'index'])  ->name('products.index');
    Route::post('/products',        [ProductController::class, 'store'])  ->name('products.store');
    Route::put('/products/{id}',    [ProductController::class, 'update']) ->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Kategori (JSON API untuk popup)
    Route::get('/categories',            [CategoryController::class, 'index'])  ->name('categories.index');
    Route::post('/categories',           [CategoryController::class, 'store'])  ->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Manajemen User
    Route::get('/users',                    [UserController::class, 'index'])          ->name('users.index');
    Route::post('/users',                   [UserController::class, 'store'])          ->name('users.store');
    Route::put('/users/{user}',             [UserController::class, 'update'])         ->name('users.update');
    Route::patch('/users/{user}/toggle',    [UserController::class, 'toggleStatus'])   ->name('users.toggle');
    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::delete('/users/{user}',          [UserController::class, 'destroy'])        ->name('users.destroy');
    Route::delete('/users-bulk',            [UserController::class, 'destroyMultiple'])->name('users.destroyMultiple');

    // Manajemen Pesanan (Order Management)
    Route::get('/orders',                       [OrderController::class, 'index'])         ->name('orders.index');
    Route::get('/orders/create',                [OrderController::class, 'create'])        ->name('orders.create');
    Route::post('/orders',                      [OrderController::class, 'store'])         ->name('orders.store');
    Route::get('/orders/users/{id}',            [OrderController::class, 'getUserDetails'])->name('orders.user-details');
    Route::patch('/orders/{order}/status',      [OrderController::class, 'updateStatus'])  ->name('orders.update-status');
    Route::patch('/orders/{order}/cancel',      [OrderController::class, 'cancel'])        ->name('orders.cancel');
    Route::delete('/orders/{order}',            [OrderController::class, 'destroy'])       ->name('orders.destroy');

    // Manajemen Keuangan & Rekap Penjualan
    Route::get('/finance',                      [\App\Http\Controllers\Admin\FinanceController::class, 'index']) ->name('finance.index');
    Route::get('/finance/export',               [\App\Http\Controllers\Admin\FinanceController::class, 'export'])->name('finance.export');
});
