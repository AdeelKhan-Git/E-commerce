<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period      = $request->get('period', 'monthly');   // daily |weekly | monthly | yearly
        $category_id = $request->get('category_id');
        $product_id  = $request->get('product_id');

        // ── Date grouping based on period ──────────────────
        [$dateFormat, , $rangeStart] = match($period) {
            'daily'   => ['%d %b', 'DATE(created_at)', now()->subDays(30)],
            'weekly'  => ['%x-W%v', null, now()->subWeeks(12)],
            'yearly'  => ['%Y', null, now()->subYears(5)],
            default   => ['%b %Y', null, now()->subMonths(12)],
        };

        // ── Base order query with filters ──────────────────
        $baseQuery = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $rangeStart);

        if ($category_id || $product_id) {
            $baseQuery->whereHas('items', function ($q) use ($category_id, $product_id) {
                if ($product_id) {
                    $q->where('product_id', $product_id);
                }
                if ($category_id) {
                    $q->whereHas('product', fn($p) => $p->where('category_id', $category_id));
                }
            });
        }

        // ── Revenue over time ────────────────────
        $revenue_trend = (clone $baseQuery)
            ->selectRaw("DATE_FORMAT(created_at, '$dateFormat') as label, SUM(total_amount) as revenue, COUNT(*) as orders, MIN(created_at) as sort_date")
            ->groupByRaw("DATE_FORMAT(created_at, '$dateFormat')")
            ->orderBy('sort_date')
            ->get();

        // ── Top selling products (filtered) ────────────────
        $top_products = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', $rangeStart)
            ->when($category_id, fn($q) => $q->where('products.category_id', $category_id))
            ->when($product_id, fn($q) => $q->where('products.id', $product_id))
            ->select(
                'products.id',
                'products.product_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.product_name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // ── Top categories (filtered) ──────────────────────
        $top_categories = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', $rangeStart)
            ->when($category_id, fn($q) => $q->where('categories.id', $category_id))
            ->when($product_id, fn($q) => $q->where('products.id', $product_id))
            ->select(
                'categories.id',
                'categories.category_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.category_name')
            ->orderByDesc('total_revenue')
            ->get();

        // ── Summary cards (filtered) ───────────────────────
        $total_revenue = (clone $baseQuery)->sum('total_amount');
        $total_orders  = (clone $baseQuery)->count();
        $avg_order     = $total_orders > 0 ? $total_revenue / $total_orders : 0;

        $total_items_sold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', $rangeStart)
            ->when($category_id, function ($q) use ($category_id) {
                $q->whereHas('product', fn($p) => $p->where('category_id', $category_id));
            })
            ->when($product_id, fn($q) => $q->where('product_id', $product_id))
            ->sum('quantity');

        // ── Dropdowns ────────────────────
        $categories = Category::orderBy('category_name')->get();
        $products   = Product::orderBy('product_name')->get();

        return view('admin.reports.index', compact(
            'period', 'category_id', 'product_id',
            'revenue_trend', 'top_products', 'top_categories',
            'total_revenue', 'total_orders', 'avg_order', 'total_items_sold',
            'categories', 'products'
        ));
    }
}