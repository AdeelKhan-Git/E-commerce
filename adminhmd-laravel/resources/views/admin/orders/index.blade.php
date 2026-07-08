@extends('layouts.admin')
@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Track and manage customer orders.')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Order # or customer..."
                value="{{ $search }}">
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">All Statuses</option>
                @foreach (['pending', 'processing', 'shipped', 'completed', 'cancelled'] as $s)
                    <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            @if ($search || $status)
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-danger"><i
                        class="bi bi-x"></i></a>
            @endif
        </form>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addOrderModal">
            <i class="bi bi-plus-lg"></i> Add Order
        </button>
    </div>

    {{-- Metrics --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <i class="bi bi-bag-check metric-icon"></i>
                <div class="metric-label">Total Orders</div>
                <div class="metric-value">{{ number_format($total_orders) }}</div>
                <small>all time</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <i class="bi bi-hourglass-split metric-icon"></i>
                <div class="metric-label">Pending</div>
                <div class="metric-value">{{ number_format($total_pending) }}</div>
                <small>awaiting processing</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <i class="bi bi-currency-dollar metric-icon"></i>
                <div class="metric-label">Paid</div>
                <div class="metric-value">{{ number_format($total_paid) }}</div>
                <small>completed payments</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-danger">
                <i class="bi bi-graph-up-arrow metric-icon"></i>
                <div class="metric-label">Revenue</div>
                <div class="metric-value">${{ number_format($total_revenue, 2) }}</div>
                <small>from paid orders</small>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-title"><i class="bi bi-table me-2"></i>Order List</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
             <thead>
                <tr><th>Order #</th><th>Customer</th><th>Amount</th><th colspan="2">Status (editable)</th><th>Method</th><th>Date</th><th class="text-end">Action</th></tr>
            </thead>
                <tbody>
                    @forelse($orders as $order)
                @php
                    $pc = ['paid'=>'success','pending'=>'warning','failed'=>'danger'][$order->payment_status] ?? 'secondary';
                    $oc = ['completed'=>'success','processing'=>'primary','shipped'=>'info','pending'=>'warning','cancelled'=>'secondary'][$order->order_status] ?? 'secondary';
                @endphp
                <tr>
                    <td><span class="badge bg-dark">{{ $order->order_number }}</span></td>
                    <td>
                        <strong>{{ $order->user?->username ?? 'Guest' }}</strong>
                        <div class="text-muted small">{{ $order->user?->email }}</div>
                    </td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
 
                    {{-- Inline Status Update Form --}}
                    <td colspan="2">
                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="d-flex gap-2 align-items-center flex-wrap">
                            @csrf @method('PUT')
                            <select name="payment_status" class="form-select form-select-sm" style="width:auto;">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid"    {{ $order->payment_status === 'paid'    ? 'selected' : '' }}>Paid</option>
                                <option value="failed"  {{ $order->payment_status === 'failed'  ? 'selected' : '' }}>Failed</option>
                            </select>
                            <select name="order_status" class="form-select form-select-sm" style="width:auto;">
                                <option value="pending"    {{ $order->order_status === 'pending'    ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped"    {{ $order->order_status === 'shipped'    ? 'selected' : '' }}>Shipped</option>
                                <option value="completed"  {{ $order->order_status === 'completed'  ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled"  {{ $order->order_status === 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary" title="Save and notify customer if changed">
                                <i class="bi bi-check-lg"></i> Update
                            </button>
                        </form>
                    </td>
 
                    <td>{{ ucwords(str_replace('_', ' ', $order->payment_method ?? '—')) }}</td>
                    <td>{{ $order->created_at?->format('M d, Y') }}</td>
                    <td class="text-end">
                        <button class="btn btn-light btn-action" data-bs-toggle="modal" data-bs-target="#editOrderModal"
                            data-id="{{ $order->id }}"
                            data-order-number="{{ $order->order_number }}"
                            data-payment-status="{{ $order->payment_status }}"
                            data-order-status="{{ $order->order_status }}"
                            data-shipping="{{ $order->shipping_address }}"
                            data-billing="{{ $order->billing_address }}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <form method="POST"
                            action="{{ route('admin.orders.destroy', $order) }}"
                            class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button
                                type="button"
                                class="btn btn-danger btn-action delete-order-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteOrderModal"
                                data-url="{{ route('admin.orders.destroy', $order) }}"
                                data-name="{{ $order->order_number }}">

                                <i class="bi bi-trash"></i> Delete

                            </button>

                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr>
                @endforelse
 
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>

    {{-- Add Order Modal --}}
    <div class="modal fade" id="addOrderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add Order</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.orders.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Order Number</label><input type="text"
                                    class="form-control" name="order_number" placeholder="ORD-001"></div>
                            <div class="col-md-6">
                                <label class="form-label">Customer</label>
                                <select class="form-select" name="user_id">
                                    <option value="">— Select Customer —</option>
                                    @foreach (\App\Models\User::orderBy('username')->get() as $u)
                                        <option value="{{ $u->id }}">{{ $u->username }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6"><label class="form-label">Total Amount</label><input type="text"
                                    class="form-control" name="total_amount" placeholder="0.00"></div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select" name="payment_method">
                                    <option value="">— Select —</option>
                                    <option value="cash_on_delivery">Cash on Delivery</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="striped">Stripe</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Status</label>
                                <select class="form-select" name="payment_status">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Order Status</label>
                                <select class="form-select" name="order_status">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-12"><label class="form-label">Shipping Address</label>
                                <textarea class="form-control" name="shipping_address" rows="2"></textarea>
                            </div>
                            <div class="col-12"><label class="form-label">Billing Address</label>
                                <textarea class="form-control" name="billing_address" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add
                            Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Order Modal --}}
    <div class="modal fade" id="editOrderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Order</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editOrderForm">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Order Number</label><input type="text"
                                    class="form-control" name="order_number" id="editOrderNumber"></div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Status</label>
                                <select class="form-select" name="payment_status" id="editPaymentStatus">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Order Status</label>
                                <select class="form-select" name="order_status" id="editOrderStatus">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-12"><label class="form-label">Shipping Address</label>
                                <textarea class="form-control" name="shipping_address" id="editShipping" rows="2"></textarea>
                            </div>
                            <div class="col-12"><label class="form-label">Billing Address</label>
                                <textarea class="form-control" name="billing_address" id="editBilling" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{{-- Delete Order Modal --}}
<div class="modal fade" id="deleteOrderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"
            style="border:none;border-radius:22px;overflow:hidden;">

            <div class="modal-body text-center p-5">

                <div
                    style="
                    width:90px;
                    height:90px;
                    background:#ffe5e5;
                    border-radius:50%;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    margin:auto;
                    ">

                    <i class="bi bi-trash-fill"
                        style="font-size:40px;color:#dc3545;"></i>

                </div>

                <h3 class="fw-bold mt-4 mb-2">
                    Delete Order?
                </h3>

                <p class="text-muted mb-1">
                    Order
                </p>

                <strong id="deleteOrderName"></strong>

                <p class="text-muted mt-3 mb-4">
                    This action cannot be undone.
                </p>

                <div class="d-flex justify-content-center gap-3">

                    <button
                        class="btn btn-light px-4"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <form
                        id="deleteOrderForm"
                        method="POST">

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-danger px-4">

                            <i class="bi bi-trash me-1"></i>

                            Delete

                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.getElementById('editOrderModal').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('editOrderForm').action = '/admin/orders/' + btn.dataset.id;
            document.getElementById('editOrderNumber').value = btn.dataset.orderNumber;
            document.getElementById('editPaymentStatus').value = btn.dataset.paymentStatus;
            document.getElementById('editOrderStatus').value = btn.dataset.orderStatus;
            document.getElementById('editShipping').value = btn.dataset.shipping;
            document.getElementById('editBilling').value = btn.dataset.billing;
        });
        document.querySelectorAll('.delete-order-btn').forEach(button => {

        button.addEventListener('click', function () {

            document.getElementById('deleteOrderForm')
                .action = this.dataset.url;

            document.getElementById('deleteOrderName')
                .textContent = this.dataset.order;

        });

    });
    </script>
@endpush
