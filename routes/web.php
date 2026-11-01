<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ── Halaman Publik (tidak perlu login) ──────────────────────────
Route::get('/',              [PageController::class, 'home'])    ->name('home');
Route::get('/katalog',       [PageController::class, 'catalog']) ->name('catalog');
Route::get('/katalog/{id}',  [PageController::class, 'show'])    ->name('product.show');
Route::get('/cerita-kami',   [PageController::class, 'about'])   ->name('about');

// ── Auth (hanya untuk yang belum login) ─────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/admin/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'login'])    ->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin Panel (wajib login) ────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/products',         [ProductController::class, 'index'])  ->name('products.index');
    Route::post('/products',        [ProductController::class, 'store'])  ->name('products.store');
    Route::put('/products/{id}',    [ProductController::class, 'update']) ->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});
