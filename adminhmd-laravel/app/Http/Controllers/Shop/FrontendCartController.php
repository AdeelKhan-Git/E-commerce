<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;

class FrontendCartController extends Controller
{
    //
        public function index()
    {
        $cart_items = Cart::with('product.primaryImage')
            ->where('user_id', auth()->id())
            ->get()
            ->map(function ($item) {
                $item->subtotal = $item->quantity * ($item->product->price ?? 0);
                return $item;
            });

        $grand_total = $cart_items->sum('subtotal');
        $cart_count  = $cart_items->sum('quantity');
        $categories  = Category::orderBy('category_name')->get();

        return view('shop.cart', compact('cart_items', 'grand_total', 'cart_count', 'categories'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->isactive) {
            return back()->with('error', 'Product not available!');
        }

        $existing = Cart::where('user_id', auth()->id())
                        ->where('product_id', $request->product_id)
                        ->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'user_id'    => auth()->id(),
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
                'created_at' => now(),
            ]);
        }

        return back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        if ($cart->user_id !== auth()->id()) abort(403);

        $cart->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Cart updated!');
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) abort(403);
        $cart->delete();
        return back()->with('success', 'Item removed!');
    }
}
