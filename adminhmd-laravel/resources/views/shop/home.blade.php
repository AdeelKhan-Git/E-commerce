@extends('layouts.shop')
@section('title', 'Home')

@section('content')

    {{-- Hero --}}
    <section class="hero p-0">

        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">

            {{-- Indicators --}}
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>

                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>

                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>

                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3">
                </button>
            </div>

            <div class="carousel-inner">

                {{-- Slide 1 --}}
                <div class="carousel-item active">
                    <div class="hero-slide slide1">

                        <img src="{{ asset('uploads/banner1.png') }}" class="hero-banner" alt="Banner 1">

                        <div class="hero-overlay"></div>

                        <a href="{{ route('shop') }}" class="btn-primary-custom"
                            style="position:absolute;left:70px;bottom:55px;z-index:20;background:#000;color:#fff;">
                            Shop Now
                        </a>

                    </div>
                </div>

                {{-- Slide 2 --}}
                <div class="carousel-item">
                    <div class="hero-slide slide2">

                        <img src="{{ asset('uploads/banner2.png') }}" class="hero-banner" alt="Banner 2">

                        <div class="hero-overlay"></div>

                        <a href="{{ route('shop') }}" class="btn-primary-custom"
                            style="position:absolute;left:70px;bottom:55px;z-index:20;background:#000;color:#fff;">
                            Explore
                        </a>

                    </div>
                </div>

                {{-- Slide 3 --}}
                <div class="carousel-item">
                    <div class="hero-slide slide3">

                        <img src="{{ asset('uploads/banner3.png') }}" class="hero-banner" alt="Banner 3">

                        <div class="hero-overlay"></div>

                        <a href="{{ route('shop', ['hot' => 1]) }}" class="btn-primary-custom"
                            style="position:absolute;left:70px;bottom:55px;z-index:20;background:#000;color:#fff;">
                            Shop Deals
                        </a>

                    </div>
                </div>

                {{-- Slide 4 --}}
                <div class="carousel-item">
                    <div class="hero-slide slide4">

                        <img src="{{ asset('uploads/banner4.png') }}" class="hero-banner" alt="Banner 4">

                        <div class="hero-overlay"></div>

                        <a href="{{ route('shop') }}" class="btn-primary-custom"
                            style="position:absolute;left:70px;bottom:55px;z-index:20;background:#000;color:#fff;">
                            View Collection
                        </a>

                    </div>
                </div>

            </div>

            {{-- Previous --}}
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">

                <span class="carousel-control-prev-icon"></span>

            </button>

            {{-- Next --}}
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">

                <span class="carousel-control-next-icon"></span>

            </button>

        </div>

    </section>

    {{-- Trust Badges --}}
    <section style="background:var(--alice-blue);padding:30px 0;border-bottom:1px solid var(--border);">
        <div class="container">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <i class="bi bi-truck trust-icon d-block mb-2"></i>
                    <strong style="color:var(--stormy-teal);font-size:14px;">Free Delivery</strong>
                    <p style="color:var(--text-muted);font-size:12px;margin:0;">On orders over $50</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="bi bi-shield-check trust-icon d-block mb-2"></i>
                    <strong style="color:var(--stormy-teal);font-size:14px;">Secure Payment</strong>
                    <p style="color:var(--text-muted);font-size:12px;margin:0;">100% protected</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="bi bi-arrow-repeat trust-icon d-block mb-2"></i>
                    <strong style="color:var(--stormy-teal);font-size:14px;">Easy Returns</strong>
                    <p style="color:var(--text-muted);font-size:12px;margin:0;">30-day policy</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="bi bi-headset trust-icon d-block mb-2"></i>
                    <strong style="color:var(--stormy-teal);font-size:14px;">24/7 Support</strong>
                    <p style="color:var(--text-muted);font-size:12px;margin:0;">Always here to help</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    @if ($categories->isNotEmpty())
        <section style="padding:50px 0;">
            <div class="container">
                <h2 class="section-title">Browse <span class="accent">Categories</span></h2>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('shop') }}" class="category-pill {{ !request('category_id') ? 'active' : '' }}">
                        All Products
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('shop', ['category_id' => $cat->id]) }}"
                            class="category-pill {{ request('category_id') == $cat->id ? 'active' : '' }}">
                            {{ $cat->category_name }}
                            <span style="opacity:.6;font-size:11px;"> ({{ $cat->products_count }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- New Arrivals --}}
    <section style="padding:10px 0 60px;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">New <span class="accent">Arrivals</span></h2>
                <a href="{{ route('shop') }}" class="btn-primary-custom" style="font-size:13px;padding:8px 20px;">View
                    All</a>
            </div>
            @if ($new_products->isEmpty())
                <p style="color:var(--text-muted);">No products yet.</p>
            @else
                <div class="row g-4">
                    @foreach ($new_products as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card">
                                <div class="product-card-img">
                                    @if ($product->primaryImage)
                                        <img src="{{ asset('uploads/' . $product->primaryImage->file_name) }}"
                                            alt="{{ $product->product_name }}">
                                    @else
                                        <i class="bi bi-image" style="font-size:50px;color:var(--border);"></i>
                                    @endif
                                </div>
                                <div class="product-card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span
                                            class="product-category">{{ $product->category?->category_name ?? 'General' }}</span>
                                        @if ($product->ishot)
                                            <span class="hot-badge">HOT</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('product.show', $product) }}"
                                        class="product-name">{{ $product->product_name }}</a>
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
                                        <a href="{{ route('login') }}"
                                            class="btn-add-cart d-block text-center text-decoration-none">
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
    @if ($hot_products->isNotEmpty())
        <section style="padding:40px 0 60px;background:var(--almond-silk);">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title mb-0" style="color:var(--stormy-teal);">
                        <i class="bi bi-fire me-2" style="color:var(--tangerine-dream);"></i>Hot <span
                            style="color:var(--tangerine-dream);">Deals</span>
                    </h2>
                    <a href="{{ route('shop') }}" class="btn-warm" style="font-size:13px;padding:8px 20px;">View All</a>
                </div>
                <div class="row g-4">
                    @foreach ($hot_products as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card" style="border-color:rgba(226,149,120,.4);">
                                <div class="product-card-img">
                                    @if ($product->primaryImage)
                                        <img src="{{ asset('uploads/' . $product->primaryImage->file_name) }}"
                                            alt="{{ $product->product_name }}">
                                    @else
                                        <i class="bi bi-image" style="font-size:50px;color:var(--border);"></i>
                                    @endif
                                </div>
                                <div class="product-card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span
                                            class="product-category">{{ $product->category?->category_name ?? 'General' }}</span>
                                        <span class="hot-badge">HOT</span>
                                    </div>
                                    <a href="{{ route('product.show', $product) }}"
                                        class="product-name">{{ $product->product_name }}</a>
                                    <div class="product-price mb-2" style="color:var(--tangerine-dream);">
                                        ${{ number_format($product->price, 2) }}</div>
                                    @auth
                                        <form method="POST" action="{{ route('cart.add') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn-add-cart"
                                                style="background:var(--tangerine-dream);">
                                                <i class="bi bi-cart-plus me-1"></i>Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="btn-add-cart d-block text-center text-decoration-none"
                                            style="background:var(--tangerine-dream);">
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
