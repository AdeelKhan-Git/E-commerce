<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Category;
use App\Models\Cart;


class FrontendOrderController extends Controller
{
    //
     public function index()
    {
       $orders = Order::where('user_id', auth()->id())
        ->with('items.product.primaryImage')
        ->latest()
        ->paginate(10);

        $cart_count = Cart::where('user_id', auth()->id())->sum('quantity');
        $categories = Category::orderBy('category_name')->get();

        return view('shop.orders', compact('orders', 'cart_count', 'categories'));
    }
}
