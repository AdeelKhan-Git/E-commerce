<?php

namespace App\Http\Controllers\Shop;


use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail; 
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Jobs\SendOrderConfirmationJob;

class FrontendCheckoutController extends Controller
{
    //
       public function index()
    {
        $cart_items = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get()
            ->map(fn($i) => tap($i, fn($i) => $i->subtotal = $i->quantity * ($i->product->price ?? 0)));

        if ($cart_items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $grand_total = $cart_items->sum('subtotal');
        $cart_count  = $cart_items->sum('quantity');
        $user        = auth()->user();
        $categories  = Category::orderBy('category_name')->get();

        return view('shop.checkout', compact('cart_items', 'grand_total', 'cart_count', 'user', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'email'     => 'required|email',
            'phone'     => 'required',
            'address'   => 'required',
            'city'      => 'required',
        ]);

        $cart_items = Cart::with('product')->where('user_id', auth()->id())->get();
        if ($cart_items->isEmpty()) return redirect()->route('cart.index');

        $total = $cart_items->sum(fn($i) => $i->quantity * $i->product->price);

        $order = Order::create([
            'order_number'    => 'ORD-' . strtoupper(Str::random(8)),
            'user_id'         => auth()->id(),
            'total_amount'    => $total,
            'payment_status'  => 'pending',
            'order_status'    => 'pending',
            'payment_method'  => 'cash_on_delivery',
            'shipping_address'=>  $request->address . ', ' . $request->city,
            'billing_address' =>  $request->address . ', ' . $request->city,
            'created_by'      => auth()->id(),
        ]);

        foreach ($cart_items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->product->price,
                'subtotal'   => $item->quantity * $item->product->price,
                'created_at' => now(),
            ]);
        }
   

        Cart::where('user_id', auth()->id())->delete();

        SendOrderConfirmationJob::dispatch($order);

        return redirect()->route('orders.index')
            ->with('success', 'Order placed! Order number: ' . $order->order_number);
    }
  
  
  
    public function stripe(Request $request)
{
    $request->validate([
        'full_name' => 'required',
        'email'     => 'required|email',
        'phone'     => 'required',
        'address'   => 'required',
        'city'      => 'required',
    ]);

    $cartItems = Cart::with('product')
        ->where('user_id', auth()->id())
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'error' => 'Your cart is empty.'
        ], 400);
    }

    $total = $cartItems->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });

    Stripe::setApiKey(config('services.stripe.secret'));

    $paymentIntent = PaymentIntent::create([
        'amount' => intval($total * 100),
        'currency' => 'usd',

        'automatic_payment_methods' => [
            'enabled' => true,
        ],

        'metadata' => [
            'user_id' => auth()->id(),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
        ],
    ]);

    return response()->json([
        'clientSecret' => $paymentIntent->client_secret,
    ]);
}
    


public function stripeSuccess(Request $request)
{
    $request->validate([
        'payment_intent' => 'required',
    ]);

    // Prevent duplicate orders
    $existingOrder = Order::where(
        'transaction_id',
        $request->payment_intent
    )->first();

    if ($existingOrder) {

        return response()->json([
            'success' => true,
            'redirect' => route('orders.index')
        ]);

    }

    $cartItems = Cart::with('product')
        ->where('user_id', auth()->id())
        ->get();

    if ($cartItems->isEmpty()) {

        return response()->json([
            'success' => false,
            'message' => 'Cart is empty.'
        ]);

    }

    $total = $cartItems->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });

    $order = Order::create([

        'order_number' => 'ORD-' . strtoupper(Str::random(8)),
        'user_id' => auth()->id(),
        'total_amount' => $total,
        'payment_status' => 'paid',
        'order_status' => 'pending',
        'payment_method' => 'striped',
        'transaction_id' => $request->payment_intent,
        'shipping_address' => $request->address . ', ' . $request->city,
        'billing_address' =>$request->address . ', ' .$request->city,
        'created_by' => auth()->id(),

    ]);

    foreach ($cartItems as $item) {

        OrderItem::create([

            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->product->price,
            'subtotal' => $item->quantity * $item->product->price,

        ]);

    }

    $order->load([
        'user',
        'items.product'
    ]);

    try {

        Mail::to($order->user->email)
            ->send(new OrderConfirmationMail($order));

    } catch (\Exception $e) {

        // Ignore mail errors
        dd($e->getMessage());

    }

    Cart::where('user_id', auth()->id())->delete();
    
    return redirect('/my-orders');
}
}
