<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\ReportController;
//------------------------------------------------------------------

use App\Http\Controllers\Shop\FrontendHomeController;
use App\Http\Controllers\Shop\FrontendProductController;
use App\Http\Controllers\Shop\FrontendCartController;
use App\Http\Controllers\Shop\FrontendCheckoutController;
use App\Http\Controllers\Shop\FrontendOrderController;


// ── Public home ──────────
// Route::get('/', function () {
//     return redirect()->route('admin.dashboard');
// });

// Breeze expects this route
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');


// ── Admin routes (auth + admin middleware) ──────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        //report
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // Users
        Route::get('/users',              [UserController::class, 'index'])->name('users.index');
        Route::post('/users',             [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',       [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',    [UserController::class, 'destroy'])->name('users.destroy');

        // Categories
        Route::get('/categories',               [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories',              [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}',    [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Products
        Route::get('/products',              [ProductController::class, 'index'])->name('products.index');
        Route::post('/products',             [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}',    [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Attachments
        Route::post('/products/{product}/attachments',                      [ProductController::class, 'uploadAttachment'])->name('products.attachments.upload');
        Route::post('/products/{product}/attachments/{attachment}/primary', [ProductController::class, 'setPrimary'])->name('products.attachments.primary');
        Route::delete('/attachments/{attachment}',                          [ProductController::class, 'deleteAttachment'])->name('products.attachments.destroy');

        // Orders
        Route::get('/orders',            [OrderController::class, 'index'])->name('orders.index');
        Route::post('/orders',           [OrderController::class, 'store'])->name('orders.store');
        Route::put('/orders/{order}',    [OrderController::class, 'update'])->name('orders.update');
        Route::put('/orders/{order}/status',   [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    });


Route::middleware(['redirect.if.admin'])->group(function () {
// ── Public shop routes ─────────────────────────────────────
Route::get('/', [FrontendHomeController::class, 'index'])->name('home');
Route::get('/shop', [FrontendHomeController::class, 'shop'])->name('shop');
Route::get('/product/{product}', [FrontendProductController::class, 'show'])->name('product.show');

// ── Auth required shop routes ──────────────────────────────
Route::middleware(['auth'])->group(function () {
    // Cart
    Route::get('/cart',                [FrontendCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add',           [FrontendCartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cart}',         [FrontendCartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}',      [FrontendCartController::class, 'remove'])->name('cart.remove');


    // Checkout
        Route::get('/checkout',            [FrontendCheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout',           [FrontendCheckoutController::class, 'store'])->name('checkout.store');
        Route::post('/checkout/stripe',    [FrontendCheckoutController::class, 'stripe'])->name('checkout.stripe');
        Route::get('/checkout/stripe/success', [FrontendCheckoutController::class, 'stripeSuccess'])->name('checkout.stripe.success');
        Route::get('/checkout/stripe/cancel', [FrontendCheckoutController::class, 'stripeCancel'])->name('checkout.stripe.cancel');

    // Orders
    Route::get('/my-orders',           [FrontendOrderController::class, 'index'])->name('orders.index');
});
});
// ── Breeze auth routes ─────────
require __DIR__.'/auth.php';