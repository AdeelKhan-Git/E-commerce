<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop') | AdminHMD Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --bg-primary: #0d0d0d;
            --bg-secondary: #1a1a1a;
            --bg-card: #1e1e1e;
            --bg-hover: #252525;
            --neon: #00f5ff;
            --neon-pink: #ff006e;
            --neon-green: #39ff14;
            --text-primary: #f0f0f0;
            --text-muted: #888;
            --border: #2a2a2a;
        }
        * { box-sizing: border-box; }
        body { background: var(--bg-primary); color: var(--text-primary); font-family: 'Segoe UI', sans-serif; margin: 0; }

        /* Navbar */
        .shop-navbar { background: var(--bg-secondary); border-bottom: 1px solid var(--border); padding: 12px 0; position: sticky; top: 0; z-index: 1000; }
        .navbar-brand-text { font-size: 22px; font-weight: 800; color: var(--neon); text-decoration: none; letter-spacing: 2px; }
        .navbar-brand-text span { color: var(--neon-pink); }
        .search-box { background: var(--bg-card); border: 1px solid var(--border); color: var(--text-primary); border-radius: 25px; padding: 8px 20px; width: 300px; }
        .search-box:focus { outline: none; border-color: var(--neon); box-shadow: 0 0 10px rgba(0,245,255,.2); color: var(--text-primary); background: var(--bg-card); }
        .search-btn { background: var(--neon); border: none; border-radius: 25px; padding: 8px 20px; color: #000; font-weight: 600; cursor: pointer; }
        .cart-badge { background: var(--neon-pink); color: #fff; border-radius: 50%; width: 20px; height: 20px; font-size: 11px; display: inline-flex; align-items: center; justify-content: center; }
        .nav-link-shop { color: var(--text-muted) !important; text-decoration: none; transition: .2s; padding: 5px 12px; border-radius: 20px; }
        .nav-link-shop:hover { color: var(--neon) !important; background: rgba(0,245,255,.05); }
        .nav-link-shop.active { color: var(--neon) !important; }

        /* Hero */
        .hero { background: linear-gradient(135deg, #0d0d0d 0%, #1a0533 50%, #0d1a33 100%); padding: 80px 0; position: relative; overflow: hidden; }
        .hero::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(ellipse at center, rgba(0,245,255,.05) 0%, transparent 60%); }
        .hero-title { font-size: 52px; font-weight: 900; line-height: 1.1; }
        .hero-title .neon-text { color: var(--neon); text-shadow: 0 0 20px rgba(0,245,255,.5); }
        .hero-title .pink-text { color: var(--neon-pink); text-shadow: 0 0 20px rgba(255,0,110,.5); }
        .hero-subtitle { color: var(--text-muted); font-size: 18px; margin: 20px 0 35px; }
        .btn-neon { background: transparent; border: 2px solid var(--neon); color: var(--neon); padding: 12px 30px; border-radius: 30px; font-weight: 700; transition: .3s; text-decoration: none; display: inline-block; }
        .btn-neon:hover { background: var(--neon); color: #000; box-shadow: 0 0 20px rgba(0,245,255,.4); }
        .btn-neon-pink { border-color: var(--neon-pink); color: var(--neon-pink); }
        .btn-neon-pink:hover { background: var(--neon-pink); color: #fff; box-shadow: 0 0 20px rgba(255,0,110,.4); }

        /* Product Cards */
        .product-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; transition: .3s; height: 100%; }
        .product-card:hover { border-color: var(--neon); box-shadow: 0 0 20px rgba(0,245,255,.1); transform: translateY(-4px); }
        .product-card-img { height: 220px; overflow: hidden; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; }
        .product-card-img img { width: 100%; height: 100%; object-fit: cover; transition: .3s; }
        .product-card:hover .product-card-img img { transform: scale(1.05); }
        .product-card-body { padding: 15px; }
        .product-name { color: var(--text-primary); font-weight: 600; text-decoration: none; display: block; margin-bottom: 5px; }
        .product-name:hover { color: var(--neon); }
        .product-price { color: var(--neon); font-size: 20px; font-weight: 700; }
        .product-category { color: var(--text-muted); font-size: 12px; margin-bottom: 5px; }
        .hot-badge { background: var(--neon-pink); color: #fff; font-size: 10px; padding: 2px 8px; border-radius: 10px; font-weight: 700; }
        .btn-add-cart { background: var(--neon); color: #000; border: none; border-radius: 20px; padding: 8px 16px; font-weight: 600; font-size: 13px; transition: .2s; width: 100%; }
        .btn-add-cart:hover { background: #00d4dd; box-shadow: 0 0 15px rgba(0,245,255,.3); }

        /* Section Titles */
        .section-title { font-size: 28px; font-weight: 800; margin-bottom: 30px; }
        .section-title .accent { color: var(--neon); }

        /* Category Pills */
        .category-pill { background: var(--bg-card); border: 1px solid var(--border); color: var(--text-muted); padding: 8px 20px; border-radius: 25px; text-decoration: none; transition: .2s; display: inline-block; font-size: 14px; }
        .category-pill:hover, .category-pill.active { background: rgba(0,245,255,.1); border-color: var(--neon); color: var(--neon); }

        /* Sidebar */
        .shop-sidebar { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 20px; }
        .sidebar-title { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--neon); margin-bottom: 15px; }

        /* Cart */
        .cart-item { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 15px; margin-bottom: 15px; }
        .cart-total-box { background: var(--bg-card); border: 1px solid var(--neon); border-radius: 12px; padding: 25px; }

        /* Checkout */
        .checkout-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 25px; }
        .form-control-dark { background: var(--bg-secondary); border: 1px solid var(--border); color: var(--text-primary); border-radius: 8px; padding: 10px 15px; width: 100%; }
        .form-control-dark:focus { outline: none; border-color: var(--neon); box-shadow: 0 0 8px rgba(0,245,255,.2); background: var(--bg-secondary); color: var(--text-primary); }
        .form-label-dark { color: var(--text-muted); font-size: 13px; margin-bottom: 5px; display: block; }

        /* Payment tabs */
        .pay-tab { background: var(--bg-secondary); border: 2px solid var(--border); color: var(--text-muted); padding: 15px; border-radius: 10px; cursor: pointer; text-align: center; transition: .2s; }
        .pay-tab.active { border-color: var(--neon); color: var(--neon); background: rgba(0,245,255,.05); }
        .pay-section { display: none; }
        .pay-section.active { display: block; }

        /* Order cards */
        .order-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
        .order-card-header { background: var(--bg-secondary); padding: 15px 20px; border-bottom: 1px solid var(--border); }
        .order-number { font-family: monospace; background: var(--neon-pink); color: #fff; padding: 3px 10px; border-radius: 5px; font-size: 13px; }

        /* Footer */
        .shop-footer { background: var(--bg-secondary); border-top: 1px solid var(--border); padding: 40px 0 20px; margin-top: 60px; }
        .footer-title { color: var(--neon); font-weight: 700; margin-bottom: 15px; }
        .footer-link { color: var(--text-muted); text-decoration: none; display: block; margin-bottom: 8px; font-size: 14px; }
        .footer-link:hover { color: var(--neon); }

        /* Badges */
        .badge-neon { background: rgba(0,245,255,.15); color: var(--neon); border: 1px solid var(--neon); padding: 3px 10px; border-radius: 20px; font-size: 11px; }
        .badge-pink { background: rgba(255,0,110,.15); color: var(--neon-pink); border: 1px solid var(--neon-pink); padding: 3px 10px; border-radius: 20px; font-size: 11px; }

        /* Misc */
        .text-neon { color: var(--neon); }
        .text-pink { color: var(--neon-pink); }
        .border-neon { border-color: var(--neon) !important; }
        .bg-dark-card { background: var(--bg-card); }
        .pagination .page-link { background: var(--bg-card); border-color: var(--border); color: var(--text-muted); }
        .pagination .page-link:hover { background: var(--bg-hover); color: var(--neon); border-color: var(--neon); }
        .pagination .page-item.active .page-link { background: var(--neon); border-color: var(--neon); color: #000; }
        .alert-success { background: rgba(57,255,20,.1); border-color: var(--neon-green); color: var(--neon-green); }
        .alert-danger  { background: rgba(255,0,110,.1); border-color: var(--neon-pink); color: var(--neon-pink); }
        .order-card-header small.text-muted {
          color: #00f5ff !important;
        }
        .btn-close {
            filter: invert(1);
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="shop-navbar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <a href="{{ route('home') }}" class="navbar-brand-text">HMD<span>STORE</span></a>

            <form action="{{ route('shop') }}" method="GET" class="d-flex gap-2 flex-grow-1 justify-content-center" style="max-width:400px;">
                <input type="text" name="search" class="search-box flex-grow-1" placeholder="Search products..." value="{{ request('search') }}">
                <button type="submit" class="search-btn"><i class="bi bi-search"></i></button>
            </form>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" class="nav-link-shop {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('shop') }}" class="nav-link-shop {{ request()->routeIs('shop') ? 'active' : '' }}">Shop</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="nav-link-shop {{ request()->routeIs('orders.*') ? 'active' : '' }}">Orders</a>
                    <a href="{{ route('cart.index') }}" class="nav-link-shop position-relative">
                        <i class="bi bi-cart3 fs-5"></i>
                        @if(isset($cart_count) && $cart_count > 0)
                            <span class="cart-badge position-absolute top-0 start-100 translate-middle">{{ $cart_count }}</span>
                        @endif
                    </a>
                    <div class="dropdown">
                        <button class="nav-link-shop dropdown-toggle" data-bs-toggle="dropdown" style="background:none;border:none;cursor:pointer;">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->username }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="background:var(--bg-card);border-color:var(--border);">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="color:var(--neon-pink);">
                                        <i class="bi bi-box-arrow-right"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link-shop">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-neon" style="font-size:13px;padding:6px 18px;">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Alerts --}}
@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif
@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@yield('content')

{{-- Footer --}}
<footer class="shop-footer">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="navbar-brand-text mb-3">HMD<span>STORE</span></div>
                <p style="color:var(--text-muted);font-size:14px;">Your trusted dark-themed store for quality products at great prices.</p>
            </div>
            <div class="col-lg-4">
                <div class="footer-title">Quick Links</div>
                <a href="{{ route('home') }}" class="footer-link">Home</a>
                <a href="{{ route('shop') }}" class="footer-link">Shop</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="footer-link">My Orders</a>
                @else
                    <a href="{{ route('login') }}" class="footer-link">Sign In</a>
                    <a href="{{ route('register') }}" class="footer-link">Register</a>
                @endauth
            </div>
            <div class="col-lg-4">
                <div class="footer-title">Categories</div>
                @foreach(\App\Models\Category::limit(5)->get() as $cat)
                    <a href="{{ route('shop', ['category_id' => $cat->id]) }}" class="footer-link">{{ $cat->category_name }}</a>
                @endforeach
            </div>
        </div>
        <div class="text-center" style="border-top:1px solid var(--border);padding-top:20px;color:var(--text-muted);font-size:13px;">
            &copy; {{ date('Y') }} HMDStore. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>