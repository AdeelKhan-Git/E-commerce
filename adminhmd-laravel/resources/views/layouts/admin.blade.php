<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AdminHMD') | AdminHMD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 250px; min-height: 100vh; background: #1a1a2e; position: fixed; top: 0; left: 0; z-index: 100; transition: .3s; }
        .sidebar-brand { padding: 20px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-brand h4 { color: #fff; margin: 0; font-weight: 700; }
        .sidebar-brand small { color: rgba(255,255,255,.5); font-size: 11px; }
        .sidebar-nav { padding: 15px 0; }
        .sidebar-nav .nav-link { color: rgba(255,255,255,.7); padding: 10px 20px; display: flex; align-items: center; gap: 10px; border-radius: 0; transition: .2s; font-size: 14px; }
        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active { color: #fff; background: rgba(255,255,255,.1); border-left: 3px solid #4e73df; }
        .sidebar-nav .nav-link i { font-size: 16px; width: 20px; }
        .sidebar-user { padding: 15px 20px; border-top: 1px solid rgba(255,255,255,.1); position: absolute; bottom: 0; width: 100%; }
        .sidebar-user p { color: rgba(255,255,255,.7); margin: 0; font-size: 13px; }
        .sidebar-user strong { color: #fff; }
        .main-content { margin-left: 250px; min-height: 100vh; }
        .top-navbar { background: #fff; padding: 12px 25px; border-bottom: 1px solid #e3e6f0; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 .15rem 1.75rem rgba(58,59,69,.15); }
        .page-content { padding: 25px; }
        .metric-card { border: none; border-radius: 10px; padding: 20px; color: #fff; margin-bottom: 20px; }
        .metric-card .metric-label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; opacity: .8; }
        .metric-card .metric-value { font-size: 28px; font-weight: 700; margin: 5px 0; }
        .metric-card .metric-icon { font-size: 40px; opacity: .3; float: right; margin-top: -10px; }
        .metric-primary { background: linear-gradient(135deg, #4e73df, #224abe); }
        .metric-success { background: linear-gradient(135deg, #1cc88a, #13855c); }
        .metric-warning { background: linear-gradient(135deg, #f6c23e, #dda20a); }
        .metric-danger  { background: linear-gradient(135deg, #e74a3b, #be2617); }
        .panel { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 .15rem 1.75rem rgba(58,59,69,.05); }
        .panel-title { font-size: 16px; font-weight: 600; color: #333; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f0; }
        .btn-action { padding: 4px 10px; font-size: 12px; }
        .badge-status { padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .alert { border-radius: 8px; }
        .table th { font-size: 12px; text-transform: uppercase; letter-spacing: .5px; color: #666; border-top: none; }
        .modal-header { background: #1a1a2e; color: #fff; }
        .modal-header .btn-close { filter: invert(1); }
        
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <h4><i class="bi bi-grid-1x2-fill me-2"></i>AdminHMD</h4>
        <small>Admin Panel</small>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Products
        </a>
        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i> Orders
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categories
        </a>
         <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i> Reports
        </a>
    </nav>
    <div class="sidebar-user">
        <p><small>Logged in as</small></p>
        <strong>{{ auth()->user()->username }}</strong>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light w-100">
                <i class="bi bi-box-arrow-right"></i> Sign Out
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <div>
            <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
            <small class="text-muted">@yield('page-subtitle', '')</small>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">{{ now()->format('D, M d Y') }}</span>
            <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> {{ auth()->user()->username }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-content">
        {{-- Success / Error alerts --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                  <i class="bi bi-exclamation-octagon-fill me-2"></i>
                         <ul class="mb-0">
                @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                @endforeach
                 </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-primary alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>