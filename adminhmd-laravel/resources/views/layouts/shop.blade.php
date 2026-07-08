<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop') | HMDStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --stormy-teal:     #006d77;
            --pearl-aqua:      #83c5be;
            --alice-blue:      #edf6f9;
            --almond-silk:     #ffddd2;
            --tangerine-dream: #e29578;

            /* Mapped roles */
            --bg-primary:   #f5fafb;
            --bg-secondary: #edf6f9;
            --bg-card:      #ffffff;
            --bg-hover:     #e0f0f3;
            --text-primary: #1a2e30;
            --text-muted:   #5a7a7e;
            --border:       #c8e3e7;
            --accent:       #006d77;
            --accent-light: #83c5be;
            --accent-warm:  #e29578;
            --accent-pale:  #ffddd2;
        }

        * { box-sizing: border-box; }
        body { background: var(--bg-primary); color: var(--text-primary); font-family: 'Segoe UI', sans-serif; margin: 0; }

        /* ── Navbar ─────────────────────────────────────── */
        .shop-navbar { background: var(--stormy-teal); border-bottom: 3px solid var(--pearl-aqua); padding: 12px 0; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 12px rgba(0,109,119,.2); }
        .navbar-brand-text { font-size: 22px; font-weight: 900; color: #fff; text-decoration: none; letter-spacing: 2px; }
        .navbar-brand-text span { color: var(--almond-silk); }
        .search-box { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color: #fff; border-radius: 25px; padding: 8px 20px; width: 300px; }
        .search-box::placeholder { color: rgba(255,255,255,.6); }
        .search-box:focus { outline: none; border-color: var(--almond-silk); box-shadow: 0 0 10px rgba(255,221,210,.3); color: #fff; background: rgba(255,255,255,.2); }
        .search-btn { background: var(--almond-silk); border: none; border-radius: 25px; padding: 8px 20px; color: var(--stormy-teal); font-weight: 700; cursor: pointer; transition: .2s; }
        .search-btn:hover { background: var(--tangerine-dream); color: #fff; }
        .cart-badge { background: var(--tangerine-dream); color: #fff; border-radius: 50%; width: 20px; height: 20px; font-size: 11px; display: inline-flex; align-items: center; justify-content: center; }
        .nav-link-shop { color: rgba(255,255,255,.8) !important; text-decoration: none; transition: .2s; padding: 5px 12px; border-radius: 20px; font-size: 14px; }
        .nav-link-shop:hover { color: #fff !important; background: rgba(255,255,255,.15); }
        .nav-link-shop.active { color: var(--almond-silk) !important; font-weight: 600; }

        /* ── Hero ───────────────────────────────────────── */
 
        .btn-primary-custom { background: var(--pearl-aqua); color: var(--stormy-teal); border: none; padding: 12px 30px; border-radius: 30px; font-weight: 700; transition: .3s; text-decoration: none; display: inline-block; }
        .btn-primary-custom:hover { background: #fff; color: var(--stormy-teal); box-shadow: 0 8px 25px rgba(131,197,190,.4); transform: translateY(-2px); }
        .btn-warm { background: var(--tangerine-dream); color: #fff; border: none; padding: 12px 30px; border-radius: 30px; font-weight: 700; transition: .3s; text-decoration: none; display: inline-block; }
        .btn-warm:hover { background: #d4805f; color: #fff; box-shadow: 0 8px 25px rgba(226,149,120,.4); transform: translateY(-2px); }

        /* ── Product Cards ───────────────────────────────── */
        .product-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; transition: .3s; height: 100%; }
        .product-card:hover { border-color: var(--pearl-aqua); box-shadow: 0 8px 30px rgba(0,109,119,.12); transform: translateY(-4px); }
        .product-card-img { height: 220px; overflow: hidden; background: var(--alice-blue); display: flex; align-items: center; justify-content: center; }
        .product-card-img img { width: 100%; height: 100%; object-fit: cover; transition: .3s; }
        .product-card:hover .product-card-img img { transform: scale(1.05); }
        .product-card-body { padding: 15px; }
        .product-name { color: var(--text-primary); font-weight: 600; text-decoration: none; display: block; margin-bottom: 5px; }
        .product-name:hover { color: var(--stormy-teal); }
        .product-price { color: var(--stormy-teal); font-size: 20px; font-weight: 800; }
        .product-category { color: var(--text-muted); font-size: 12px; margin-bottom: 5px; }
        .hot-badge { background: var(--tangerine-dream); color: #fff; font-size: 10px; padding: 3px 8px; border-radius: 10px; font-weight: 700; }
        .btn-add-cart { background: var(--stormy-teal); color: #fff; border: none; border-radius: 20px; padding: 9px 16px; font-weight: 600; font-size: 13px; transition: .2s; width: 100%; cursor: pointer; }
        .btn-add-cart:hover { background: #005961; box-shadow: 0 4px 15px rgba(0,109,119,.3); }

        /* ── Section Titles ──────────────────────────────── */
        .section-title { font-size: 28px; font-weight: 800; margin-bottom: 30px; color: var(--text-primary); }
        .section-title .accent { color: var(--stormy-teal); }

        /* ── Category Pills ──────────────────────────────── */
        .category-pill { background: var(--bg-card); border: 1px solid var(--border); color: var(--text-muted); padding: 8px 20px; border-radius: 25px; text-decoration: none; transition: .2s; display: inline-block; font-size: 14px; }
        .category-pill:hover, .category-pill.active { background: var(--stormy-teal); border-color: var(--stormy-teal); color: #fff; }

        /* ── Sidebar ─────────────────────────────────────── */
        .shop-sidebar { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 20px; }
        .sidebar-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--stormy-teal); margin-bottom: 15px; border-bottom: 2px solid var(--alice-blue); padding-bottom: 8px; }

        /* ── Cart ────────────────────────────────────────── */
        .cart-item { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 15px; margin-bottom: 15px; }
        .cart-total-box { background: var(--bg-card); border: 2px solid var(--pearl-aqua); border-radius: 16px; padding: 25px; }

        /* ── Checkout ────────────────────────────────────── */
        .checkout-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 25px; }
        .form-control-dark { background: var(--alice-blue); border: 1px solid var(--border); color: var(--text-primary); border-radius: 8px; padding: 10px 15px; width: 100%; transition: .2s; }
        .form-control-dark:focus { outline: none; border-color: var(--pearl-aqua); box-shadow: 0 0 8px rgba(131,197,190,.3); background: #fff; }
        .form-label-dark { color: var(--text-muted); font-size: 13px; margin-bottom: 5px; display: block; font-weight: 500; }

        /* ── Payment tabs ────────────────────────────────── */
        .pay-tab { background: var(--alice-blue); border: 2px solid var(--border); color: var(--text-muted); padding: 15px; border-radius: 12px; cursor: pointer; text-align: center; transition: .2s; }
        .pay-tab:hover { border-color: var(--pearl-aqua); color: var(--stormy-teal); }
        .pay-tab.active { border-color: var(--stormy-teal); color: var(--stormy-teal); background: rgba(0,109,119,.06); font-weight: 600; }
        .pay-section { display: none; }
        .pay-section.active { display: block; }

        /* ── Order cards ─────────────────────────────────── */
        .order-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; margin-bottom: 20px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,109,119,.05); }
        .order-card-header { background: var(--alice-blue); padding: 15px 20px; border-bottom: 1px solid var(--border); }
        .order-number { font-family: monospace; background: var(--stormy-teal); color: #fff; padding: 3px 10px; border-radius: 5px; font-size: 13px; }

        /* ── Breadcrumb ──────────────────────────────────── */
        .breadcrumb-link { color: var(--text-muted); text-decoration: none; }
        .breadcrumb-link:hover { color: var(--stormy-teal); }
        .breadcrumb-sep { color: var(--border); margin: 0 8px; }
        .breadcrumb-active { color: var(--stormy-teal); font-weight: 600; }

        /* ── Footer ──────────────────────────────────────── */
        .shop-footer { background: var(--stormy-teal); padding: 50px 0 20px; margin-top: 60px; }
        .footer-title { color: var(--almond-silk); font-weight: 700; margin-bottom: 15px; font-size: 15px; letter-spacing: .5px; }
        .footer-link { color: rgba(255,255,255,.7); text-decoration: none; display: block; margin-bottom: 8px; font-size: 14px; transition: .2s; }
        .footer-link:hover { color: var(--almond-silk); padding-left: 4px; }
        .footer-brand { font-size: 24px; font-weight: 900; color: #fff; letter-spacing: 2px; }
        .footer-brand span { color: var(--almond-silk); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.15); padding-top: 20px; margin-top: 30px; }

        /* ── Badges ──────────────────────────────────────── */
        .badge-teal { background: rgba(0,109,119,.1); color: var(--stormy-teal); border: 1px solid var(--pearl-aqua); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-warm { background: var(--almond-silk); color: var(--tangerine-dream); border: 1px solid var(--tangerine-dream); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }

        /* ── Alerts ──────────────────────────────────────── */
        .alert-success { background: rgba(131,197,190,.15); border-color: var(--pearl-aqua); color: var(--stormy-teal); border-radius: 10px; }
        .alert-danger  { background: rgba(226,149,120,.15); border-color: var(--tangerine-dream); color: #c0644a; border-radius: 10px; }

        /* ── Pagination ──────────────────────────────────── */
        .pagination .page-link { background: var(--bg-card); border-color: var(--border); color: var(--text-muted); }
        .pagination .page-link:hover { background: var(--alice-blue); color: var(--stormy-teal); border-color: var(--pearl-aqua); }
        .pagination .page-item.active .page-link { background: var(--stormy-teal); border-color: var(--stormy-teal); color: #fff; }

        /* ── Misc ────────────────────────────────────────── */
        .text-teal { color: var(--stormy-teal); }
        .text-warm { color: var(--tangerine-dream); }
        .section-bg-warm { background: var(--almond-silk); }
        .trust-icon { color: var(--pearl-aqua); font-size: 28px; }
        /* Status Badges */
        .status-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:6px 14px;
            border-radius:999px;
            font-size:12px;
            font-weight:700;
            color:#fff;
            min-width:90px;
        }

        .status-completed{
            background:#198754;
        }

        .status-pending{
            background:var(--tangerine-dream);
        }

        .status-processing{
            background:var(--stormy-teal);
        }

        .status-cancelled{
            background:#dc3545;
        }
        .link-secondary{
            color:var(--stormy-teal);
            text-decoration:none;
            font-weight:600;
            transition:.25s;
        }

        .link-secondary:hover{
            color:var(--tangerine-dream);
            text-decoration:none;
        }
        a{
            color:var(--stormy-teal);
            text-decoration:none;
        }

        a:hover{
            color:var(--tangerine-dream);
        }
        .btn-outline-custom{
            background:#fff;
            color:var(--stormy-teal);
            border:1px solid var(--pearl-aqua);
            border-radius:10px;
            padding:7px 16px;
            font-size:13px;
            font-weight:600;
            transition:.25s ease;
            cursor:pointer;
        }

        .btn-outline-custom:hover{
            background:var(--stormy-teal);
            color:#fff;
            border-color:var(--stormy-teal);
        }

        .btn-outline-custom:active{
            transform:scale(.98);
        }
        .pay-tab{
            background:#fff;
            border:2px solid var(--border);
            border-radius:16px;
            padding:22px 16px;
            text-align:center;
            cursor:pointer;
            transition:.25s;
            color:var(--text-muted);
        }

        .pay-tab i{
            font-size:28px;
            margin-bottom:10px;
            color:var(--stormy-teal);
        }

        .pay-tab:hover{
            border-color:var(--pearl-aqua);
            background:var(--alice-blue);
        }

        .pay-tab.active{
            background:rgba(0,109,119,.06);
            border-color:var(--stormy-teal);
            color:var(--stormy-teal);
            font-weight:700;
            box-shadow:0 6px 20px rgba(0,109,119,.12);
        }
        /* ==========================
        Cart Responsive
        ========================== */

        .cart-row{
            position:relative;
        }

        @media (max-width:768px){

            .cart-row{
                align-items:flex-start !important;
            }

            .cart-row form{
                width:100%;
            }

            .cart-row .btn-outline-custom{
                width:100%;
            }

            .cart-row > div:last-of-type{
                width:100%;
                text-align:left !important;
                margin-top:10px;
            }

            .remove-cart-btn{
                position:absolute;
                top:8px;
                right:8px;
                font-size:22px !important;
                padding:6px !important;
            }
        }
       /* ======================================================
        HERO CAROUSEL
        ====================================================== */

        .hero{
            padding:0;
            margin:0;
            overflow:hidden;
            background:none;
        }

        #heroCarousel{
            width:100%;
            position:relative;
        }

        /* ===========================
        Slides
        =========================== */

        .carousel-item{
            height:70vh;
            min-height:320px;
            max-height:650px;
        }

        .hero-slide{
            position:relative;
            width:100%;
            height:100%;
            overflow:hidden;
        }

        /* ===========================
        Banner Image
        =========================== */

        .hero-banner{
            width:100%;
            height:100%;
            display:block;
            object-fit:cover;
            object-position:center center;
        }

        /* Different crop positions if needed */

        .slide1 .hero-banner{
            object-position:center center;
        }

        .slide2 .hero-banner{
            object-position:right center;
        }

        .slide3 .hero-banner{
            object-position:center center;
        }

        .slide4 .hero-banner{
            object-position:center center;
        }

        /* ===========================
        Overlay
        =========================== */

        .hero-slide::after{
            content:"";
            position:absolute;
            inset:0;
            background:rgba(0,0,0,.10);
            z-index:1;
        }

        /* ===========================
        Hero Content
        =========================== */

        .hero-content{
            position:absolute;
            left:70px;
            bottom:70px;
            z-index:5;
            max-width:520px;
        }

        .hero-title{
            font-size:56px;
            font-weight:900;
            line-height:1.1;
            color:#fff;
            margin-bottom:18px;
        }

        .hero-title .accent-teal{
            color:var(--pearl-aqua);
        }

        .hero-title .accent-warm{
            color:var(--almond-silk);
        }

        .hero-subtitle{
            color:rgba(255,255,255,.95);
            font-size:20px;
            margin-bottom:28px;
        }

        .hero-btn{
            display:inline-block;
            padding:14px 38px;
            border-radius:50px;
            font-size:18px;
            font-weight:700;
            text-decoration:none;
        }

        /* ===========================
        Controls
        =========================== */

        .carousel-control-prev,
        .carousel-control-next{
            width:6%;
            opacity:0;
            transition:.3s;
            z-index:20;
        }

        #heroCarousel:hover .carousel-control-prev,
        #heroCarousel:hover .carousel-control-next{
            opacity:1;
        }

        /* ===========================
        Indicators
        =========================== */

        .carousel-indicators{
            margin-bottom:20px;
        }

        .carousel-indicators button{
            width:12px;
            height:12px;
            border-radius:50%;
            border:none;
        }

        /* ======================================================
        TABLET
        ====================================================== */

        @media (max-width:992px){

            .carousel-item{
                height:55vh;
                min-height:420px;
            }

            .hero-content{
                left:40px;
                bottom:45px;
                max-width:400px;
            }

            .hero-title{
                font-size:42px;
            }

            .hero-subtitle{
                font-size:18px;
            }

            .hero-btn{
                padding:12px 30px;
                font-size:16px;
            }
        }

        /* ======================================================
        MOBILE
        ====================================================== */

        @media (max-width:768px){

            .carousel-item{
                height:45vh;
                min-height:260px;
                max-height:360px;
            }

            .hero-slide{
                height:100%;
            }

            .hero-banner{
                object-fit:cover;
                object-position:center center;
            }

            .hero-content{
                left:18px;
                right:18px;
                bottom:18px;
                max-width:100%;
            }

            .hero-title{
                font-size:28px;
                margin-bottom:10px;
            }

            .hero-subtitle{
                font-size:15px;
                margin-bottom:18px;
            }

            .hero-btn{
                padding:10px 22px;
                font-size:14px;
                border-radius:30px;
            }

            .carousel-control-prev,
            .carousel-control-next{
                display:none;
            }

            .carousel-indicators{
                margin-bottom:10px;
            }

            .carousel-indicators button{
                width:9px;
                height:9px;
            }

            /* Mobile crop adjustments */

            .slide1 .hero-banner{
                object-position:center;
            }

            .slide2 .hero-banner{
                object-position:75% center;
            }

            .slide3 .hero-banner{
                object-position:center;
            }

            .slide4 .hero-banner{
                object-position:center;
            }
        }

        /* ======================================================
        SMALL MOBILE
        ====================================================== */

        @media (max-width:480px){

            .carousel-item{
                height:38vh;
                min-height:220px;
            }

            .hero-content{
                left:12px;
                right:12px;
                bottom:12px;
            }

            .hero-title{
                font-size:22px;
            }

            .hero-subtitle{
                font-size:13px;
                margin-bottom:12px;
            }

            .hero-btn{
                padding:8px 18px;
                font-size:13px;
            }
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
                    <a href="{{ route('orders.index') }}" class="nav-link-shop {{ request()->routeIs('orders.*') ? 'active' : '' }}">My Orders</a>
                    <a href="{{ route('cart.index') }}" class="nav-link-shop position-relative">
                        <i class="bi bi-cart3 fs-5"></i>
                        @if(isset($cart_count) && $cart_count > 0)
                            <span class="cart-badge position-absolute top-0 start-100 translate-middle">{{ $cart_count }}</span>
                        @endif
                    </a>
                    <div class="dropdown">
                        <button class="nav-link-shop dropdown-toggle" data-bs-toggle="dropdown" style="background:none;border:none;cursor:pointer;">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->username }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="background:#fff;border-color:var(--border);border-radius:12px;box-shadow:0 8px 25px rgba(0,109,119,.15);">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="color:var(--tangerine-dream);font-weight:600;">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link-shop">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-primary-custom" style="font-size:13px;padding:7px 20px;">Register</a>
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
                <div class="footer-brand mb-3">HMD<span>STORE</span></div>
                <p style="color:rgba(255,255,255,.65);font-size:14px;line-height:1.7;">Your trusted online store for quality products at great prices.</p>
            </div>
            <div class="col-lg-4">
                <div class="footer-title">Quick Links</div>
                <a href="{{ route('home') }}" class="footer-link"><i class="bi bi-chevron-right me-1" style="font-size:11px;"></i>Home</a>
                <a href="{{ route('shop') }}" class="footer-link"><i class="bi bi-chevron-right me-1" style="font-size:11px;"></i>Shop</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="footer-link"><i class="bi bi-chevron-right me-1" style="font-size:11px;"></i>My Orders</a>
                @else
                    <a href="{{ route('login') }}" class="footer-link"><i class="bi bi-chevron-right me-1" style="font-size:11px;"></i>Sign In</a>
                    <a href="{{ route('register') }}" class="footer-link"><i class="bi bi-chevron-right me-1" style="font-size:11px;"></i>Register</a>
                @endauth
            </div>
            <div class="col-lg-4">
                <div class="footer-title">Categories</div>
                @foreach(\App\Models\Category::limit(5)->get() as $cat)
                    <a href="{{ route('shop', ['category_id' => $cat->id]) }}" class="footer-link">
                        <i class="bi bi-chevron-right me-1" style="font-size:11px;"></i>{{ $cat->category_name }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="footer-bottom text-center">
            <span style="color:rgba(255,255,255,.5);font-size:13px;">&copy; {{ date('Y') }} HMDStore. All rights reserved.</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>