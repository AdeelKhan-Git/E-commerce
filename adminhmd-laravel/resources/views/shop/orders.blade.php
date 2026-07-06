@extends('layouts.shop')
@section('title', 'My Orders')

@section('content')

    <div class="container" style="padding:50px 0;">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
            <div>
                <h2 class="section-title mb-1">
                    My <span class="accent">Orders</span>
                </h2>

                <p style="color:var(--text-muted);margin:0;">
                    {{ $orders->count() }} order{{ $orders->count() != 1 ? 's' : '' }} placed
                </p>
            </div>

            <a href="{{ route('shop') }}" class="btn-neon">
                <i class="bi bi-bag-plus me-2"></i>
                Continue Shopping
            </a>
        </div>

        @if ($orders->isEmpty())

            <div class="checkout-card text-center py-5">

                <i class="bi bi-bag-x" style="font-size:70px;color:var(--border);"></i>

                <h3 class="mt-4">No Orders Yet</h3>

                <p style="color:var(--text-muted);">
                    You haven't placed any orders yet.
                </p>

                <a href="{{ route('shop') }}" class="btn-neon mt-3">
                    Browse Products
                </a>

            </div>
        @else
            @foreach ($orders as $order)
                @php

                    $orderStatus = strtolower($order->order_status ?? 'pending');
                    $paymentStatus = strtolower($order->payment_status ?? 'pending');

                    $statusClass = match ($orderStatus) {
                        'pending' => 'badge-pink',
                        'processing' => 'badge-neon',
                        'completed' => 'bg-success',
                        'shipped' => 'bg-info',
                        'cancelled' => 'bg-danger',
                        default => 'bg-secondary',
                    };

                    $paymentClass = match ($paymentStatus) {
                        'paid' => 'bg-success',
                        'pending' => 'bg-warning text-dark',
                        'failed' => 'bg-danger',
                        default => 'bg-secondary',
                    };

                @endphp

                <div class="order-card mb-4">

                    {{-- Header --}}
                    <div class="order-card-header">

                        <div class="row w-100 align-items-center gy-3">

                            <div class="col-lg-9">

                                <div class="row gy-3">

                                    <div class="col-md-3">

                                        <small class="text-muted d-block">
                                            Order Number
                                        </small>

                                        <span class="order-number">
                                            {{ $order->order_number }}
                                        </span>

                                    </div>

                                    <div class="col-md-2">

                                        <small class="text-muted d-block">
                                            Date
                                        </small>

                                        <strong>
                                            {{ $order->created_at->format('d M Y') }}
                                        </strong>

                                    </div>

                                    <div class="col-md-2">

                                        <small class="text-muted d-block">
                                            Total
                                        </small>

                                        <span class="product-price">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </span>

                                    </div>

                                    <div class="col-md-2">

                                        <small class="text-muted d-block">
                                            Payment
                                        </small>

                                        <span class="badge {{ $paymentClass }}">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>

                                    </div>

                                    <div class="col-md-3">

                                        <small class="text-muted d-block">
                                            Status
                                        </small>

                                        @if ($statusClass == 'badge-neon')
                                            <span class="badge-neon">
                                                {{ ucfirst($orderStatus) }}
                                            </span>
                                        @elseif($statusClass == 'badge-pink')
                                            <span class="badge-pink">
                                                {{ ucfirst($orderStatus) }}
                                            </span>
                                        @else
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst($orderStatus) }}
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-3 text-lg-end">

                                <button class="btn-neon" style="padding:8px 20px;font-size:13px;"
                                    onclick="toggleOrder({{ $order->id }})" id="btn-{{ $order->id }}">

                                    <i class="bi bi-chevron-down"></i>

                                    Details

                                </button>

                            </div>

                        </div>

                    </div>

                    {{-- Hidden Details --}}

                    <div id="order-{{ $order->id }}" style="display:none;">

                        <div class="p-4">

                            <h5 class="mb-4 text-neon">

                                <i class="bi bi-box-seam me-2"></i>

                                Ordered Products

                            </h5>
                            @foreach ($order->items as $item)
                                <div class="cart-item mb-3">

                                    <div class="row align-items-center">

                                        <div class="col-md-2 col-4">

                                            <div
                                                style="height:90px;
                                        background:var(--bg-secondary);
                                        border-radius:10px;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        overflow:hidden;">

                                                @if ($item->product && $item->product->primaryImage)
                                                    <img src="{{ asset('uploads/' . $item->product->primaryImage->file_name) }}"
                                                        style="width:100%;height:100%;object-fit:cover;">
                                                @else
                                                    <i class="bi bi-image" style="font-size:35px;color:var(--border);"></i>
                                                @endif

                                            </div>

                                        </div>

                                        <div class="col-md-5 col-8">

                                            <h6 class="mb-2">

                                                {{ $item->product->product_name }}

                                            </h6>

                                            <div style="color:var(--text-muted);font-size:14px;">

                                                Quantity :
                                                <strong>{{ $item->quantity }}</strong>

                                            </div>

                                            <div style="color:var(--text-muted);font-size:14px;">

                                                Unit Price :
                                                ${{ number_format($item->unit_price, 2) }}

                                            </div>

                                        </div>

                                        <div class="col-md-3 mt-3 mt-md-0">

                                            <span class="badge-neon">

                                                × {{ $item->quantity }}

                                            </span>

                                        </div>

                                        <div class="col-md-2 text-md-end mt-3 mt-md-0">

                                            <div class="product-price">

                                                ${{ number_format($item->subtotal, 2) }}

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                            <hr style="border-color:var(--border);">

                            <div class="row gy-4">

                                <div class="col-lg-6">

                                    <div class="checkout-card h-100">

                                        <h6 class="text-neon mb-3">

                                            <i class="bi bi-geo-alt-fill me-2"></i>

                                            Shipping Address

                                        </h6>

                                        <p style="margin:0;color:var(--text-muted);line-height:1.8;">

                                            {{ $order->shipping_address ?? 'N/A' }}

                                        </p>

                                    </div>

                                </div>

                                <div class="col-lg-6">

                                    <div class="checkout-card h-100">

                                        <h6 class="text-neon mb-3">

                                            <i class="bi bi-credit-card me-2"></i>

                                            Payment Information

                                        </h6>

                                        <table class="table table-borderless mb-0">

                                            <tr>

                                                <td style="color:var(--text-muted);">

                                                    Method

                                                </td>

                                                <td class="text-end">

                                                    {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}

                                                </td>

                                            </tr>

                                            <tr>

                                                <td style="color:var(--text-muted);">

                                                    Payment

                                                </td>

                                                <td class="text-end">

                                                    <span class="badge {{ $paymentClass }}">

                                                        {{ ucfirst($paymentStatus) }}

                                                    </span>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td style="color:var(--text-muted);">

                                                    Order Status

                                                </td>

                                                <td class="text-end">

                                                    @if ($statusClass == 'badge-neon')
                                                        <span class="badge-neon">

                                                            {{ ucfirst($orderStatus) }}

                                                        </span>
                                                    @elseif($statusClass == 'badge-pink')
                                                        <span class="badge-pink">

                                                            {{ ucfirst($orderStatus) }}

                                                        </span>
                                                    @else
                                                        <span class="badge {{ $statusClass }}">

                                                            {{ ucfirst($orderStatus) }}

                                                        </span>
                                                    @endif

                                                </td>

                                            </tr>

                                            <tr>

                                                <td style="font-weight:bold;">

                                                    Grand Total

                                                </td>

                                                <td class="text-end product-price">

                                                    ${{ number_format($order->total_amount, 2) }}

                                                </td>

                                            </tr>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        @endif

    </div>

@endsection

@push('scripts')
    <script>
        function toggleOrder(id) {

            let card = document.getElementById('order-' + id);

            let btn = document.getElementById('btn-' + id);

            let icon = btn.querySelector('i');

            if (card.style.display === 'none' || card.style.display === '') {

                card.style.display = 'block';

                icon.classList.remove('bi-chevron-down');

                icon.classList.add('bi-chevron-up');

                btn.innerHTML = '<i class="bi bi-chevron-up me-1"></i>Hide';

            } else {

                card.style.display = 'none';

                btn.innerHTML = '<i class="bi bi-chevron-down me-1"></i>Details';

            }

        }
    </script>
@endpush
@push('styles')
<style>
    .order-card-header small.text-muted {
        color: #00f5ff !important;
    }
</style>
@endpush