<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Http\Request;

class FrontendHomeController extends Controller
{
    //
    public function index()
    {
        $categories  = Category::withCount('products')->orderBy('category_name')->get();
        $new_products = Product::with('primaryImage')
                            ->where('isactive', 1)
                            ->latest()
                            ->limit(8)
                            ->get();
        $hot_products = Product::with('primaryImage')
                            ->where('isactive', 1)
                            ->where('ishot', 1)
                            ->latest()
                            ->limit(8)
                            ->get();

        // if ($hot_products->isEmpty()) {
        //     $hot_products = Product::with('primaryImage')
        //                         ->where('isactive', 1)
        //                         ->orderByDesc('price')
        //                         ->limit(8)
        //                         ->get();
        // }

        $cart_count = $this->cartCount();

        return view('shop.home', compact('categories', 'new_products', 'hot_products', 'cart_count'));
    }

    public function shop(Request $request)
    {
        $search      = $request->get('search', '');
        $category_id = $request->get('category_id');
        $sort        = $request->get('sort', 'latest');
        $min_price   = $request->get('min_price');
        $max_price   = $request->get('max_price');

        $order = match($sort) {
            'price_asc'  => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'name_asc'   => ['product_name', 'asc'],
            'name_desc'  => ['product_name', 'desc'],
            default      => ['created_at', 'desc'],
        };

        $products = Product::with(['primaryImage', 'category'])
            ->where('isactive', 1)
            ->when($search, fn($q) => $q->where('product_name', 'like', "%$search%")
                                        ->orWhere('_description', 'like', "%$search%"))
            ->when($category_id, fn($q) => $q->where('category_id', $category_id))
            ->when($min_price, fn($q) => $q->where('price', '>=', $min_price))
            ->when($max_price, fn($q) => $q->where('price', '<=', $max_price))
            ->orderBy($order[0], $order[1])
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('category_name')->get();
        $cart_count = $this->cartCount();

        return view('shop.shop', compact(
            'products', 'categories', 'search',
            'category_id', 'sort', 'min_price', 'max_price', 'cart_count'
        ));
    }

    private function cartCount()
    {
        if (!auth()->check()) return 0;
        return Cart::where('user_id', auth()->id())->sum('quantity');
    }

}


