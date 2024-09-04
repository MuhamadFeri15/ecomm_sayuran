<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\PaymentController;
use Illuminate\Routing\RouteGroup;
use Symfony\Component\Routing\Router;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
});


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('register', [LoginController::class,'register']);
Route::get('forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [LoginController::class,'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [LoginController::class, 'resetPassword'])->name('password.update');

Route::middleware('profile')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/update-profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/edit-password', [ProfileController::class, 'editPassword'])->name('profile.editPassword');
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/trash', [ProfileController::class, 'trash'])->name('profile.trash');
    Route::get('/profile/trash/restore/{id}', [ProfileController::class, 'restore'])->name('profile.restore');
    Route::get('/profile/trash/permanent-delete/{id}', [ProfileController::class, 'permanentDelete'])->name('profile.permanentDelete');
});

Route::middleware('auth')->group(function () {
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::post('/product', [ProductController::class,'store']);
    Route::get('/product/{id}', [ProductController::class,'show'])->name('product.show');
    Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::patch('/product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('/product/trash', [ProductController::class, 'trash'])->name('product.trash');
    Route::get('product/trash/restore/{id}', [ProductController::class, 'restore'])->name('product.restore');
    Route::get('/product/trash/permanent-delete/{id}', [ProductController::class, 'permanentDelete'])->name('product.permanentDelete');
    Route::get('/product/search', [ProductController::class, 'search'])->name('products.search');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/cart/{id}/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

Route::middleware(['auth'])->group(function () {
    // Menampilkan daftar item favorit pengguna
    Route::get('/favourite', [FavouriteController::class, 'index'])->name('favourite.index');

    // Menambahkan item ke daftar favorit
    Route::post('/favourite/{id}', [FavouriteController::class, 'store'])->name('favourite.store');

    // Menghapus item dari daftar favorit
    Route::delete('/favourite/{id}', [FavouriteController::class, 'destroy'])->name('favourite.destroy');

    // Menampilkan detail dari item favorit tertentu
    Route::get('/favourite/{id}', [FavouriteController::class, 'show'])->name('favourite.show');
});

Route::middleware(['auth'])->group(function () {
   Route::get('/product-stock', [ProductStockController::class, 'index'])->name('productStock.index');
   Route::get('/product-stock/{id}', [ProductStockController::class, 'edit'])->name('productStock.edit');
   Route::patch('/product-stock/{id}', [ProductStockController::class, 'update'])->name('productStock.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::patch('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/order-items', [OrderItemController::class, 'index'])->name('orderItem.index');
    Route::post('/order-items', [OrderItemController::class,'store'])->name('orderItem.store');
    Route::patch('/order-items/{id}', [OrderItemController::class, 'update'])->name('orderItem.update');
    Route::delete('/order-items/{id}', [OrderItemController::class, 'destroy'])->name('orderItem.destroy');
    Route::get('/order-items/{id}/edit', [OrderItemController::class, 'edit'])->name('orderItem.edit');
    Route::get('/order-items/{id}', [OrderItemController::class,'show'])->name('orderItem.show');
    Route::get('/order-items/{order_id}/product/{product_id}', [OrderItemController::class, 'updateQuantity'])->name('orderItem.updateQuantity');
    Route::get('/order-items/{order_id}/product/{product_id}/delete', [OrderItemController::class, 'deleteItem'])->name('orderItem.deleteItem');

});

Route::middleware(['auth'])->group(function () {

Route::get('/shippings', [ShippingController::class, 'index'])->name('shippings.index');
Route::get('/shippings/create', [ShippingController::class, 'create'])->name('shippings.create');
Route::post('/shippings', [ShippingController::class, 'store'])->name('shippings.store');
Route::get('/shippings/{id}', [ShippingController::class, 'show'])->name('shippings.show');
Route::get('/shippings/{id}/edit', [ShippingController::class, 'edit'])->name('shippings.edit');
Route::put('/shippings/{id}', [ShippingController::class, 'update'])->name('shippings.update');
Route::delete('/shippings/{id}', [ShippingController::class, 'destroy'])->name('shippings.destroy');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{id}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{id}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});


