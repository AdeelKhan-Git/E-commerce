@extends('layouts.shop')
@section('title', 'Cart')

@section('content')
        <div class="container" style="padding:40px 0;">
            <h2 class="section-title">Your <span class="accent">Cart</span></h2>

    @if ($cart_items->isEmpty())
        <div class="checkout-card text-center py-5">

            <i class="bi bi-cart-x"
            style="font-size:75px;color:var(--pearl-aqua);display:block;margin-bottom:20px;">
            </i>

            <h3 class="mb-3" style="color:var(--stormy-teal);font-weight:700;">
                Your Cart is Empty
            </h3>

            <p style="color:var(--text-muted);max-width:400px;margin:0 auto 30px;">
                Looks like you haven't added any products yet.
                Browse our collection and find something you'll love.
            </p>

            <a href="{{ route('shop') }}" class="btn-primary-custom">
                <i class="bi bi-bag me-2"></i>
                Browse Products
            </a>

        </div>
    @else
            <div class="row g-4">
                {{-- Cart Items --}}
                <div class="col-lg-8">
                    @foreach ($cart_items as $item)
                        <div class="cart-item">
                            <div class="cart-row d-flex flex-column flex-md-row gap-3 align-items-center">
                                {{-- Image --}}
                                <div
                                    style="width:80px;height:80px;border-radius:8px;overflow:hidden;background:var(--bg-secondary);flex-shrink:0;">
                                    @if ($item->product?->primaryImage)
                                        <img src="{{ asset('uploads/' . $item->product->primaryImage->file_name) }}"
                                            style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <div
                                            style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-image" style="color:var(--border);font-size:28px;"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="flex-grow-1">
                                    <a href="{{ route('product.show', $item->product) }}"
                                        style="color:var(--text-primary);text-decoration:none;font-weight:600;font-size:16px;">
                                        {{ $item->product?->product_name ?? 'Product' }}
                                    </a>
                                    <div style="color:var(--text-muted);font-size:13px;margin-top:3px;">
                                        Unit price: ${{ number_format($item->product?->price ?? 0, 2) }}
                                    </div>
                                </div>

                                {{-- Quantity --}}
                                <form method="POST" action="{{ route('cart.update', $item) }}"
                                    class="d-flex align-items-center gap-2">
                                    @csrf @method('PUT')
                                    <div
                                        style="display:flex;align-items:center;background:var(--bg-secondary);border:1px solid var(--border);border-radius:20px;overflow:hidden;">
                                        <button type="button"
                                            onclick="this.nextElementSibling.value=Math.max(1,parseInt(this.nextElementSibling.value)-1)"
                                            style="background:none;border:none;color:var(--text-primary);padding:5px 12px;cursor:pointer;">−</button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                            style="background:none;border:none;color:var(--text-primary);width:45px;text-align:center;font-size:15px;font-weight:700;">
                                        <button type="button"
                                            onclick="this.previousElementSibling.value=parseInt(this.previousElementSibling.value)+1"
                                            style="background:none;border:none;color:var(--text-primary);padding:5px 12px;cursor:pointer;">+</button>
                                    </div>
                                   <button type="submit" class="btn-outline-custom">
                                        Update
                                    </button>
                                </form>

                                {{-- Subtotal --}}
                                <div style="min-width:80px;text-align:right;">
                                    <div style="color:var(--neon);font-weight:700;font-size:18px;">
                                        ${{ number_format($item->subtotal, 2) }}</div>
                                </div>

                                {{-- Remove --}}
                                <form method="POST" action="{{ route('cart.remove', $item) }}">
                                    @csrf @method('DELETE')
                                    <button
                                        type="button"
                                        class="remove-cart-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#removeCartModal"
                                        data-url="{{ route('cart.remove', $item) }}"
                                        style="background:none;border:none;color:var(--neon-pink);cursor:pointer;font-size:20px;padding:0 5px;">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary --}}
                <div class="col-lg-4">
                    <div class="cart-total-box shadow-sm">

                    <h3 class="mb-4" style="font-weight:700;color:var(--stormy-teal);">
                        Order Summary
                    </h3>

                    <div class="d-flex justify-content-between mb-3">
                        <span style="color:var(--text-muted);">
                            Items ({{ $cart_count }})
                        </span>

                        <span style="font-weight:600;">
                            ${{ number_format($grand_total, 2) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <span style="color:var(--text-muted);">
                            Shipping
                        </span>

                        <span style="color:var(--stormy-teal);font-weight:600;">
                            Free
                        </span>
                    </div>

                    <hr style="border-color:var(--border);">

                    <div class="d-flex justify-content-between align-items-center my-4">
                        <span style="font-size:22px;font-weight:700;">
                            Total
                        </span>

                        <span class="product-price" style="font-size:42px;">
                            ${{ number_format($grand_total, 2) }}
                        </span>
                    </div>

                    <a href="{{ route('checkout.index') }}"
                        class="btn-primary-custom w-100 text-center"
                        style="padding:14px 20px;">

                        Proceed to Checkout

                        <i class="bi bi-arrow-right ms-2"></i>

                    </a>

                    <a href="{{ route('shop') }}"
                        class="link-secondary d-block text-center mt-4">

                        <i class="bi bi-arrow-left me-1"></i>

                        Continue Shopping

                    </a>

                </div>
                </div>
            </div>
        @endif
    </div>
    <!-- Remove Cart Item Modal -->
<div class="modal fade" id="removeCartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"
             style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:15px;">

            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-trash text-danger me-2"></i>
                    Remove Item
                </h5>

                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <i class="bi bi-cart-x"
                    style="font-size:55px;color:var(--neon-pink);"></i>

                <h5 class="mt-3">Remove this item from your cart?</h5>

            </div>

            <div class="modal-footer border-0 justify-content-center">

                <button class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Cancel
                </button>

                <form id="removeCartForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Remove
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.remove-cart-btn').forEach(button => {

        button.addEventListener('click', function () {

            document.getElementById('removeCartForm')
                .setAttribute('action', this.dataset.url);

        });

    });

});
</script>
