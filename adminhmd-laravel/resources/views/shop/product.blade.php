@extends('layouts.shop')
@section('title', $product->product_name)

@section('content')
    <div class="container" style="padding:40px 0;">

        {{-- Breadcrumb --}}
        <nav style="margin-bottom:30px;">
            <span><a href="{{ route('home') }}" style="color:var(--text-muted);text-decoration:none;">Home</a></span>
            <span style="color:var(--border);margin:0 8px;">/</span>
            <span><a href="{{ route('shop') }}" style="color:var(--text-muted);text-decoration:none;">Shop</a></span>
            @if ($product->category)
                <span style="color:var(--border);margin:0 8px;">/</span>
                <span><a href="{{ route('shop', ['category_id' => $product->category_id]) }}"
                        style="color:var(--text-muted);text-decoration:none;">{{ $product->category->category_name }}</a></span>
            @endif
            <span style="color:var(--border);margin:0 8px;">/</span>
            <span style="color:var(--neon);">{{ $product->product_name }}</span>
        </nav>

        <div class="row g-5">
            {{-- Left: Images --}}
            <div class="col-lg-5">
                {{-- Main Image --}}
                <div
                    style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:15px;">
                    @if ($product->productattachments->isNotEmpty())
                        <img src="{{ asset('uploads/' . $product->productattachments->where('is_primary', 1)->first()?->file_name ?? $product->productattachments->first()->file_name) }}"
                            id="main-product-img" alt="{{ $product->product_name }}"
                            style="width:100%;height:380px;object-fit:contain;background:var(--bg-card);">
                    @else
                        <div style="height:380px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-image" style="font-size:80px;color:var(--border);"></i>
                        </div>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if ($product->productattachments->count() > 1)
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach ($product->productattachments as $att)
                            <img src="{{ asset('uploads/' . $att->file_name) }}"
                                onclick="document.getElementById('main-product-img').src=this.src"
                                style="width:70px;height:70px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid {{ $att->is_primary ? 'var(--neon)' : 'var(--border)' }};transition:.2s;"
                                onmouseover="this.style.borderColor='var(--neon)'"
                                onmouseout="this.style.borderColor='{{ $att->is_primary ? 'var(--neon)' : 'var(--border)' }}'">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right: Info --}}
            <div class="col-lg-7">
                <div style="padding:10px 0;">
                    @if ($product->category)
                        <a href="{{ route('shop', ['category_id' => $product->category_id]) }}"
                            style="color:var(--text-muted);text-decoration:none;font-size:13px;">
                            <i class="bi bi-tag me-1"></i>{{ $product->category->category_name }}
                        </a>
                    @endif

                    <h1 style="font-size:32px;font-weight:800;margin:10px 0;">{{ $product->product_name }}</h1>

                    <div style="margin:15px 0;">
                        @if ($product->ishot)
                            <span class="hot-badge me-2">🔥 HOT</span>
                        @endif
                        <span class="badge-neon">In Stock</span>
                    </div>

                    <div style="font-size:36px;font-weight:900;color:var(--neon);margin:20px 0;">
                        ${{ number_format($product->price, 2) }}
                    </div>

                    @if ($product->_description)
                        <div style="color:var(--text-muted);line-height:1.8;margin-bottom:25px;font-size:15px;">
                            {{ $product->_description }}
                        </div>
                    @endif

                    {{-- Add to Cart --}}
                    @auth
                        <form method="POST" action="{{ route('cart.add') }}" class="d-flex gap-3 align-items-center mb-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div
                                style="display:flex;align-items:center;background:var(--bg-secondary);border:1px solid var(--border);border-radius:25px;overflow:hidden;">
                                <button type="button" onclick="changeQty(-1)"
                                    style="background:transparent!important;border:none;outline:none;box-shadow:none;color:var(--text-primary)!important;width:40px;height:40px;cursor:pointer;font-size:18px;line-height:1;">−</button>
                                <input type="number" name="quantity" id="qty-input" value="1" min="1"
                                    style="background:none;border:none;color:var(--text-primary);width:50px;text-align:center;font-size:16px;font-weight:700;">
                                <button type="button" onclick="changeQty(1)"
                                    style="background:transparent!important;border:none;outline:none;box-shadow:none;color:var(--text-primary)!important;width:40px;height:40px;cursor:pointer;font-size:18px;line-height:1;">+</button>
                            </div>
                            <button type="submit" class="btn-add-cart flex-grow-1" style="font-size:16px;padding:12px;">
                                <i class="bi bi-cart-plus me-2"></i>Add to Cart
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-neon d-block text-center mb-4"
                            style="font-size:16px;padding:14px;">
                            <i class="bi bi-lock me-2"></i>Sign in to Add to Cart
                        </a>
                    @endauth

                    {{-- Trust Badges --}}
                    <div class="row g-3">
                        <div class="col-4 text-center">
                            <div
                                style="background:var(--bg-card);border:1px solid var(--border);border-radius:10px;padding:15px;">
                                <i class="bi bi-shield-check" style="color:var(--neon);font-size:24px;"></i>
                                <p style="color:var(--text-muted);font-size:12px;margin:5px 0 0;">Secure Checkout</p>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div
                                style="background:var(--bg-card);border:1px solid var(--border);border-radius:10px;padding:15px;">
                                <i class="bi bi-truck" style="color:var(--neon);font-size:24px;"></i>
                                <p style="color:var(--text-muted);font-size:12px;margin:5px 0 0;">Fast Delivery</p>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div
                                style="background:var(--bg-card);border:1px solid var(--border);border-radius:10px;padding:15px;">
                                <i class="bi bi-arrow-repeat" style="color:var(--neon);font-size:24px;"></i>
                                <p style="color:var(--text-muted);font-size:12px;margin:5px 0 0;">Easy Returns</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Description Tab --}}
        <div style="margin-top:50px;">
            <ul class="nav" style="border-bottom:1px solid var(--border);margin-bottom:25px;">
                <li class="nav-item">
                    <a href="#"
                        style="color:var(--neon);border-bottom:2px solid var(--neon);padding:10px 20px;text-decoration:none;display:block;font-weight:600;">Description</a>
                </li>
                <li class="nav-item">
                    <a href="#"
                        style="color:var(--text-muted);padding:10px 20px;text-decoration:none;display:block;">Details</a>
                </li>
            </ul>
            <div style="color:var(--text-muted);line-height:1.9;font-size:15px;">
                {{ $product->_description ?? 'No description available.' }}
            </div>
        </div>

        {{-- Related Products --}}
        @if ($related->isNotEmpty())
            <div style="margin-top:60px;">
                <h2 class="section-title">Related <span class="accent">Products</span></h2>
                <div class="row g-4">
                    @foreach ($related as $rp)
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="product-card">
                                <div class="product-card-img">
                                    @if ($rp->primaryImage)
                                        <img src="{{ asset('uploads/' . $rp->primaryImage->file_name) }}"
                                            alt="{{ $rp->product_name }}">
                                    @else
                                        <i class="bi bi-image" style="font-size:40px;color:var(--border);"></i>
                                    @endif
                                </div>
                                <div class="product-card-body">
                                    <a href="{{ route('product.show', $rp) }}" class="product-name"
                                        style="font-size:13px;">{{ $rp->product_name }}</a>
                                    <div class="product-price" style="font-size:16px;">
                                        ${{ number_format($rp->price, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        function changeQty(delta) {
            const input = document.getElementById('qty-input');
            const val = parseInt(input.value) + delta;
            if (val >= 1) input.value = val;
        }
    </script>
@endpush
