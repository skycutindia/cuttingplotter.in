<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Cutting Plotter CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --primary: #1a56db; }
        body { background: #f4f6f9; }
        .sidebar { width: var(--sidebar-width); min-height: 100vh; background: #1e293b; position: fixed; top: 0; left: 0; z-index: 1000; }
        .sidebar .nav-link { color: #94a3b8; padding: 0.75rem 1.25rem; border-radius: 0.375rem; margin: 0.125rem 0.75rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .sidebar .nav-link i { width: 1.5rem; }
        .main-content { margin-left: var(--sidebar-width); }
        .stat-card { border: none; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
        .top-bar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 0.75rem 1.5rem; }
        .content-area { padding: 1.5rem; }
        .card { border: none; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="sidebar">
        <div class="p-3 border-bottom border-secondary">
            <h5 class="text-white mb-0"><i class="bi bi-printer"></i> CMS Admin</h5>
            <small class="text-muted">cuttingplotter.in</small>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam"></i> Products</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}"><i class="bi bi-tags"></i> Brands</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="bi bi-folder"></i> Categories</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}" href="{{ route('admin.leads.index') }}"><i class="bi bi-people"></i> Leads</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear"></i> Settings</a></li>
            <li class="nav-item mt-3"><a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-globe"></i> View Website</a></li>
        </ul>
    </nav>

    <div class="main-content">
        <div class="top-bar d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-muted">@yield('page-title', 'Dashboard')</h6>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>
