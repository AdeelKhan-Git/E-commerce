@extends('layouts.admin')
@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'Track sales performance by time period, category, and product.')

@section('content')

{{-- Filters --}}
<div class="panel mb-4">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small text-muted">Time Period</label>
            <select name="period" class="form-select" onchange="this.form.submit()">
                <option value="daily"  {{ $period === 'daily'  ? 'selected' : '' }}>Daily</option>
                <option value="weekly"  {{ $period === 'weekly'  ? 'selected' : '' }}>Weekly (last 12 weeks)</option>
                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly (last 12 months)</option>
                <option value="yearly"  {{ $period === 'yearly'  ? 'selected' : '' }}>Yearly (last 5 years)</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Filter by Category</label>
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $category_id == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Filter by Product</label>
            <select name="product_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Products</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ $product_id == $p->id ? 'selected' : '' }}>{{ $p->product_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            @if($category_id || $product_id)
                <a href="{{ route('admin.reports.index', ['period' => $period]) }}" class="btn btn-outline-danger w-100">
                    <i class="bi bi-x-circle"></i> Clear Filters
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card metric-primary">
            <i class="bi bi-currency-dollar metric-icon"></i>
            <div class="metric-label">Total Revenue</div>
            <div class="metric-value">${{ number_format($total_revenue, 2) }}</div>
            <small>filtered period</small>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card metric-success">
            <i class="bi bi-bag-check metric-icon"></i>
            <div class="metric-label">Total Orders</div>
            <div class="metric-value">{{ number_format($total_orders) }}</div>
            <small>paid orders</small>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card metric-warning">
            <i class="bi bi-graph-up metric-icon"></i>
            <div class="metric-label">Avg Order Value</div>
            <div class="metric-value">${{ number_format($avg_order, 2) }}</div>
            <small>per order</small>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card metric-danger">
            <i class="bi bi-box-seam metric-icon"></i>
            <div class="metric-label">Items Sold</div>
            <div class="metric-value">{{ number_format($total_items_sold) }}</div>
            <small>units sold</small>
        </div>
    </div>
</div>

{{-- Revenue Trend Chart --}}
<div class="panel mb-4">
    <div class="panel-title">
        <i class="bi bi-graph-up-arrow me-2"></i>Revenue Trend
        ({{ ucfirst($period) }})
    </div>
    @if($revenue_trend->isEmpty())
        <p class="text-muted text-center py-4">No revenue data for the selected filters.</p>
    @else
        <canvas id="revenueTrendChart" height="80"></canvas>
    @endif
</div>

<div class="row g-3">
    {{-- Top Products --}}
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="panel-title"><i class="bi bi-trophy me-2"></i>Top Selling Products</div>
            @if($top_products->isEmpty())
                <p class="text-muted text-center py-4">No product sales data.</p>
            @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>#</th><th>Product</th><th class="text-center">Units Sold</th><th class="text-end">Revenue</th></tr></thead>
                    <tbody>
                        @foreach($top_products as $i => $p)
                        <tr>
                            <td>
                                @if($i === 0) <span class="badge bg-warning text-dark">🥇 1</span>
                                @elseif($i === 1) <span class="badge bg-secondary">🥈 2</span>
                                @elseif($i === 2) <span class="badge bg-danger">🥉 3</span>
                                @else {{ $i + 1 }}
                                @endif
                            </td>
                            <td><strong>{{ $p->product_name }}</strong></td>
                            <td class="text-center"><span class="badge bg-primary">{{ $p->total_sold }}</span></td>
                            <td class="text-end text-success fw-bold">${{ number_format($p->total_revenue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Top Categories --}}
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="panel-title"><i class="bi bi-tags me-2"></i>Top Categories</div>
            @if($top_categories->isEmpty())
                <p class="text-muted text-center py-4">No category sales data.</p>
            @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>#</th><th>Category</th><th class="text-center">Units Sold</th><th class="text-end">Revenue</th></tr></thead>
                    <tbody>
                        @foreach($top_categories as $i => $c)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $c->category_name }}</strong></td>
                            <td class="text-center"><span class="badge bg-info text-dark">{{ $c->total_sold }}</span></td>
                            <td class="text-end text-success fw-bold">${{ number_format($c->total_revenue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <canvas id="categoryPieChart" height="200" class="mt-3"></canvas>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if($revenue_trend->isNotEmpty())
new Chart(document.getElementById('revenueTrendChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($revenue_trend->pluck('label')) !!},
        datasets: [{
            label: 'Revenue ($)',
            data: {!! json_encode($revenue_trend->pluck('revenue')) !!},
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78,115,223,.1)',
            fill: true,
            tension: .3,
            pointBackgroundColor: '#4e73df',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '$' + v } } }
    }
});
@endif

@if($top_categories->isNotEmpty())
new Chart(document.getElementById('categoryPieChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($top_categories->pluck('category_name')) !!},
        datasets: [{
            data: {!! json_encode($top_categories->pluck('total_revenue')) !!},
            backgroundColor: ['#4e73df','#1cc88a','#f6c23e','#e74a3b','#36b9cc','#858796','#5a5c69'],
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
@endif
</script>
@endpush