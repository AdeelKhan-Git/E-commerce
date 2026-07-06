@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Monitor performance, sales, users, and support from one clean workspace.')

@section('content')

{{-- Metric Cards --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card metric-primary">
            <i class="bi bi-currency-dollar metric-icon"></i>
            <div class="metric-label">Revenue</div>
            <div class="metric-value">${{ number_format($total_revenue, 2) }}</div>
            <small>from paid orders</small>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card metric-success">
            <i class="bi bi-bag-check metric-icon"></i>
            <div class="metric-label">Orders</div>
            <div class="metric-value">{{ number_format($total_orders) }}</div>
            <small>{{ $total_pending }} pending</small>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card metric-warning">
            <i class="bi bi-people metric-icon"></i>
            <div class="metric-label">Customers</div>
            <div class="metric-value">{{ number_format($total_users) }}</div>
            <small>registered users</small>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card metric-danger">
            <i class="bi bi-box-seam metric-icon"></i>
            <div class="metric-label">Products</div>
            <div class="metric-value">{{ number_format($total_products) }}</div>
            <small>{{ $total_categories }} categories · {{ $total_cart }} in carts</small>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Sales Chart --}}
    <div class="col-xl-8">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-graph-up-arrow me-2"></i>Sales Performance (Last 6 Months)</div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="col-xl-4">
        <div class="panel h-100">
            <div class="panel-title"><i class="bi bi-activity me-2"></i>Quick Stats</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span><i class="bi bi-people text-primary me-2"></i>Total Users</span>
                    <strong>{{ $total_users }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span><i class="bi bi-bag-check text-success me-2"></i>Total Orders</span>
                    <strong>{{ $total_orders }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span><i class="bi bi-box-seam text-warning me-2"></i>Products</span>
                    <strong>{{ $total_products }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span><i class="bi bi-tags text-info me-2"></i>Categories</span>
                    <strong>{{ $total_categories }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span><i class="bi bi-cart3 text-danger me-2"></i>Cart Items</span>
                    <strong>{{ $total_cart }}</strong>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Recent Users --}}
<div class="panel mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="panel-title mb-0"><i class="bi bi-people me-2"></i>Recent Users</div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Manage Users</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>#</th><th>Username</th><th>Email</th><th>Designation</th><th>Joined</th></tr></thead>
            <tbody>
                @forelse($recent_users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><strong>{{ $user->username }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->designation ?? '—' }}</td>
                    <td>{{ $user->created_at?->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted">No users yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Recent Orders --}}
<div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="panel-title mb-0"><i class="bi bi-bag-check me-2"></i>Recent Orders</div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($recent_orders as $order)
                <tr>
                    <td><span class="badge bg-dark">{{ $order->order_number }}</span></td>
                    <td>{{ $order->user?->username ?? 'Guest' }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        @php
                            $pc = ['paid'=>'success','pending'=>'warning','failed'=>'danger'][$order->payment_status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $pc }}">{{ ucfirst($order->payment_status) }}</span>
                    </td>
                    <td>
                        @php
                            $oc = ['completed'=>'success','processing'=>'primary','shipped'=>'info','pending'=>'warning','cancelled'=>'secondary'][$order->order_status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $oc }}">{{ ucfirst($order->order_status) }}</span>
                    </td>
                    <td>{{ $order->created_at?->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthly_revenue->pluck('month')) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($monthly_revenue->pluck('revenue')) !!},
                backgroundColor: 'rgba(78,115,223,.7)',
                borderColor: '#4e73df',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => '$' + v } } }
        }
    });
</script>
@endpush