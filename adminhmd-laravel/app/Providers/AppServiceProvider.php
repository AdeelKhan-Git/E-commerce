<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        
        // URL::forceScheme('https');
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);

        Route::model('user',     User::class);
        Route::model('category', Category::class);
        Route::model('product',  Product::class);
        Route::model('order',    Order::class);
        Route::model('cart',     Cart::class);
    }
}