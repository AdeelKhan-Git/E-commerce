<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Jobs\SendOrderStatusChangedJob;

class OrderController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
 
        $orders = Order::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('order_number', 'like', "%$search%")
                  ->orWhereHas('user', fn($u) => $u->where('username', 'like', "%$search%"));
            })
            ->when($status, fn($q) => $q->where('order_status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();
 
        $total_orders   = Order::count();
        $total_pending  = Order::where('order_status', 'pending')->count();
        $total_paid     = Order::where('payment_status', 'paid')->count();
        $total_revenue  = Order::where('payment_status', 'paid')->sum('total_amount');
 
        return view('admin.orders.index', compact(
            'orders', 'search', 'status',
            'total_orders', 'total_pending', 'total_paid', 'total_revenue'
        ));
    }
 
    //post order
    public function store(Request $request)
    {
        $request->validate([
            'order_number' => 'required|unique:orders,order_number',
            'user_id'      => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
        ]);
 
        Order::create([
            'order_number'    => $request->order_number,
            'user_id'         => $request->user_id,
            'total_amount'    => $request->total_amount,
            'payment_status'  => $request->payment_status ?? 'pending',
            'order_status'    => $request->order_status ?? 'pending',
            'payment_method'  => $request->payment_method,
            'shipping_address'=> $request->shipping_address,
            'billing_address' => $request->billing_address,
            'created_by'      => auth()->id(),
        ]);
 
        return back()->with('success', 'Order added successfully!');
    }
 
    //update order
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'order_number' => 'required|unique:orders,order_number,' . $order->id,
        ]);
 
        $order->update([
            'order_number'    => $request->order_number,
            'payment_status'  => $request->payment_status,
            'order_status'    => $request->order_status,
            'shipping_address'=> $request->shipping_address,
            'billing_address' => $request->billing_address,
            'updated_by'      => auth()->id(),
        ]);
 
        return back()->with('success', 'Order updated successfully!');
    }
 

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status'   => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);
 
        $oldOrderStatus   = $order->order_status;
        $oldPaymentStatus = $order->payment_status;
 
        $newOrderStatus   = $request->order_status;
        $newPaymentStatus = $request->payment_status;
 
        $order->update([
            'order_status'   => $newOrderStatus,
            'payment_status' => $newPaymentStatus,
            'updated_by'     => auth()->id(),
        ]);
 
        $emailsSent = [];
 
        // Only send email if order_status actually changed
        if ($oldOrderStatus !== $newOrderStatus) {
            SendOrderStatusChangedJob::dispatch($order, 'order_status', $oldOrderStatus, $newOrderStatus);
            $emailsSent[] = 'order status';
        }
 
        // Only send email if payment_status actually changed
        if ($oldPaymentStatus !== $newPaymentStatus) {
            SendOrderStatusChangedJob::dispatch($order, 'payment_status', $oldPaymentStatus, $newPaymentStatus);
            $emailsSent[] = 'payment status';
        }
 
        if (empty($emailsSent)) {
            return back()->with('success', 'No changes detected — nothing updated.');
        }
 
        return back()->with('success', 'Order updated! Customer notified about: ' . implode(' & ', $emailsSent) . '.');
    }
 
    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return back()->with('success', 'Order deleted successfully!');
    }

}

