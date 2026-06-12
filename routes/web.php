<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\FrontController;

Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/category/{id}', [FrontController::class, 'categoryProducts'])->name('category.products');
Route::get('/product/{id}', [FrontController::class, 'productDetail'])->name('product.detail');

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
});

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return '<div style="font-family: sans-serif; padding: 40px; text-align: center; background: #0f172a; color: white; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <h1 style="color: #a5b4fc; margin-bottom: 10px;">Admin Paneline Hoş Geldiniz!</h1>
                    <p style="color: #94a3b8;">Giriş yapan yetkili: <strong>' . auth()->user()->name . '</strong></p>
                    <div style="margin-top: 20px; display: flex; gap: 15px;">
                        <a href="'.route('home').'" style="padding: 8px 16px; background: #475569; color: white; text-decoration: none; border-radius: 6px;">Ana Sayfaya Dön</a>
                        <form action="' . route('logout') . '" method="POST">' . csrf_field() . '<button type="submit" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">Çıkış Yap</button></form>
                    </div>
                </div>';
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('orders/{id}', [AdminOrderController::class, 'update'])->name('admin.orders.update');
});
