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
    Route::get('/checkout', function () {
        return view('front.checkout');
    })->name('checkout.form');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
});

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('orders/{id}', [AdminOrderController::class, 'update'])->name('admin.orders.update');
});
