@extends('layouts.shop')
@section('title', 'Shop')

@section('content')
    <div class="container" style="padding:40px 0;">
        <div class="row g-4">

            {{-- Sidebar --}}
            <div class="col-lg-3">
                <div class="shop-sidebar mb-4">
                    <div class="sidebar-title">Categories</div>
                    <a href="{{ route('shop', array_merge(request()->except(['category_id', 'page']), [])) }}"
                        class="category-pill d-block mb-2 {{ !$category_id ? 'active' : '' }}">
                        All Products
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('shop', array_merge(request()->except(['category_id', 'page']), ['category_id' => $cat->id])) }}"
                            class="category-pill d-block mb-2 {{ $category_id == $cat->id ? 'active' : '' }}">
                            {{ $cat->category_name }}
                        </a>
                    @endforeach
                </div>

                {{-- Price Filter --}}
                <div class="shop-sidebar mb-4">
                    <div class="sidebar-title">Filter by Price</div>
                    <form method="GET" action="{{ route('shop') }}">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="category_id" value="{{ $category_id }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <div class="mb-2">
                            <label class="form-label-dark">Min Price ($)</label>
                            <input type="number" name="min_price" class="form-control-dark" value="{{ $min_price }}"
                                min="0" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-dark">Max Price ($)</label>
                            <input type="number" name="max_price" class="form-control-dark" value="{{ $max_price }}"
                                min="0" placeholder="9999">
                        </div>
                        <button type="submit" class="btn-primary-custom w-100" style="font-size:13px;padding:8px;">
                            <i class="bi bi-funnel me-1"></i>Apply Filter
                        </button>
                    </form>
                </div>

                @if ($search || $category_id || $min_price || $max_price)
                    <a href="{{ route('shop') }}" class="btn-warm d-block text-center" style="font-size:13px;padding:8px;">
                        <i class="bi bi-x-circle me-1"></i>Clear Filters
                    </a>
                @endif
            </div>

            {{-- Products --}}
            <div class="col-lg-9">
                {{-- Top Bar --}}
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <p style="color:var(--text-muted);margin:0;">
                        Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
                        product{{ $products->total() != 1 ? 's' : '' }}
                    </p>
                    <form method="GET" action="{{ route('shop') }}" id="sort-form">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="category_id" value="{{ $category_id }}">
                        <input type="hidden" name="min_price" value="{{ $min_price }}">
                        <input type="hidden" name="max_price" value="{{ $max_price }}">
                        <select name="sort" class="form-control-dark" style="min-width:180px;"
                            onchange="document.getElementById('sort-form').submit()">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name A–Z</option>
                            <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Name Z–A</option>
                            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price Low–High
                            </option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price High–Low
                            </option>
                        </select>
                    </form>
                </div>

                @if ($products->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-search" style="font-size:60px;color:var(--border);"></i>
                        <h4 style="color:var(--text-muted);margin-top:20px;">No products found</h4>
                        <a href="{{ route('shop') }}" class="btn-primary-custom mt-3 d-inline-block">Browse All
                            Products</a>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($products as $product)
                            <div class="col-6 col-md-4">
                                <div class="product-card h-100">
                                    <a href="{{ route('product.show', $product) }}">
                                        <div class="product-card-img">
                                            @if ($product->primaryImage)
                                                <img src="{{ asset('uploads/' . $product->primaryImage->file_name) }}"
                                                    alt="{{ $product->product_name }}">
                                            @else
                                                <i class="bi bi-image" style="font-size:50px;color:var(--border);"></i>
                                            @endif
                                        </div>
                                    </a>
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

                    {{-- Pagination --}}
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
