@extends('layouts.shop')
@section('title', 'Home')

@section('content')

{{-- Hero --}}
<section class="hero">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    Shop The <span class="neon-text">Future</span><br>
                    Buy The <span class="pink-text">Best</span>
                </h1>
                <p class="hero-subtitle">Discover our latest collection of premium products at unbeatable prices.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('shop') }}" class="btn-neon">Shop Now <i class="bi bi-arrow-right ms-2"></i></a>
                    <a href="{{ route('shop', ['hot' => 1]) }}" class="btn-neon btn-neon-pink">Hot Deals <i class="bi bi-fire ms-2"></i></a>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <div style="font-size:180px;line-height:1;opacity:.15;color:var(--neon);">
                    <i class="bi bi-bag-heart"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Categories --}}
@if($categories->isNotEmpty())
<section style="padding:50px 0;">
    <div class="container">
        <h2 class="section-title">Browse <span class="accent">Categories</span></h2>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('shop') }}" class="category-pill {{ !request('category_id') ? 'active' : '' }}">
                All Products
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('shop', ['category_id' => $cat->id]) }}"
                   class="category-pill {{ request('category_id') == $cat->id ? 'active' : '' }}">
                    {{ $cat->category_name }}
                    <span style="opacity:.6;font-size:11px;">({{ $cat->products_count }})</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- New Arrivals --}}
<section style="padding:20px 0 50px;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">New <span class="accent">Arrivals</span></h2>
            <a href="{{ route('shop') }}" class="btn-neon" style="font-size:13px;padding:8px 20px;">View All</a>
        </div>
        @if($new_products->isEmpty())
            <p style="color:var(--text-muted);">No products yet.</p>
        @else
        <div class="row g-4">
            @foreach($new_products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card">
                    <div class="product-card-img">
                        @if($product->primaryImage)
                            <img src="{{ asset('uploads/' . $product->primaryImage->file_name) }}" alt="{{ $product->product_name }}">
                        @else
                            <i class="bi bi-image" style="font-size:50px;color:var(--border);"></i>
                        @endif
                    </div>
                    <div class="product-card-body">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="product-category">{{ $product->category?->category_name ?? 'General' }}</span>
                            @if($product->ishot)<span class="hot-badge">HOT</span>@endif
                        </div>
                        <a href="{{ route('product.show', $product) }}" class="product-name">{{ $product->product_name }}</a>
                        <div class="product-price mb-2">${{ number_format($product->price, 2) }}</div>
                        @auth
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-add-cart">
                                    <i class="bi bi-cart-plus me-1"></i>Add to Cart
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-add-cart d-block text-center text-decoration-none">
                                <i class="bi bi-cart-plus me-1"></i>Add to Cart
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Hot Products --}}
@if($hot_products->isNotEmpty())
<section style="padding:20px 0 60px;background:var(--bg-secondary);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 pt-4">
            <h2 class="section-title mb-0"><span style="color:var(--neon-pink);">Hot</span> Products <i class="bi bi-fire" style="color:var(--neon-pink);"></i></h2>
            <a href="{{ route('shop') }}" class="btn-neon btn-neon-pink" style="font-size:13px;padding:8px 20px;">View All</a>
        </div>
        <div class="row g-4">
            @foreach($hot_products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card" style="border-color:rgba(255,0,110,.3);">
                    <div class="product-card-img">
                        @if($product->primaryImage)
                            <img src="{{ asset('uploads/' . $product->primaryImage->file_name) }}" alt="{{ $product->product_name }}">
                        @else
                            <i class="bi bi-image" style="font-size:50px;color:var(--border);"></i>
                        @endif
                    </div>
                    <div class="product-card-body">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="product-category">{{ $product->category?->category_name ?? 'General' }}</span>
                            <span class="hot-badge">HOT</span>
                        </div>
                        <a href="{{ route('product.show', $product) }}" class="product-name">{{ $product->product_name }}</a>
                        <div class="product-price mb-2" style="color:var(--neon-pink);">${{ number_format($product->price, 2) }}</div>
                        @auth
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-add-cart" style="background:var(--neon-pink);color:#fff;">
                                    <i class="bi bi-cart-plus me-1"></i>Add to Cart
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-add-cart d-block text-center text-decoration-none" style="background:var(--neon-pink);color:#fff;">
                                <i class="bi bi-cart-plus me-1"></i>Add to Cart
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection