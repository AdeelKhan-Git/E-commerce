<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Attachment;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    //
    public function index(){
        
        $total_users = User::count();
        $total_products = Product::count();
        $total_orders = Order::count();
        $total_revenue = Order::where('payment_status','paid')->sum('total_amount');
        $total_categories = Category::count();
        $total_cart = Cart::count();
        $total_pending = Order::where('order_status','pending')->count();

        $recent_users = User::latest()->limit(5)->get();
        $recent_orders = Order::with('user')->latest()->limit(5)->get();

        $monthly_revenue = Order::where('payment_status','paid')
        ->where('created_at','>=',now()->subMonths(6))
        ->selectRaw('DATE_FORMAT(created_at,"%b") as month, SUM(total_amount) as revenue')
        ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m"), DATE_FORMAT(created_at, "%b")')
        ->orderByRaw('MIN(created_at)')
        ->get();

        return view('admin.dashboard', compact(
            'total_users', 'total_products', 'total_orders',
            'total_revenue', 'total_categories', 'total_cart',
            'total_pending', 'recent_users', 'recent_orders',
            'monthly_revenue'
        ));
        
    }

}

