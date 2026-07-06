<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;

class FrontendProductController extends Controller
{
    //
    public function show(Product $product)
    {
        if (!$product->isactive) abort(404);

        $product->load(['productattachments', 'category']);

        $related = Product::with('primaryImage')
            ->where('isactive', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();

        $categories = Category::orderBy('category_name')->get();
        $cart_count = auth()->check()
            ? Cart::where('user_id', auth()->id())->sum('quantity')
            : 0;

        return view('shop.product', compact('product', 'related', 'categories', 'cart_count'));
    }
}


