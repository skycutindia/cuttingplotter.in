<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings['default_meta_title'] ?? 'Cutting Plotter India')</title>
    <meta name="description" content="@yield('meta_description', $settings['default_meta_description'] ?? '')">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #1a56db; --dark: #1e293b; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar { background: var(--dark) !important; }
        .navbar-brand { font-weight: 700; color: #fff !important; }
        .nav-link { color: rgba(255,255,255,0.85) !important; font-weight: 500; }
        .nav-link:hover { color: #fff !important; }
        .hero-section { background: linear-gradient(135deg, #1e293b 0%, #1a56db 100%); color: #fff; padding: 5rem 0; }
        .hero-section h1 { font-size: 2.75rem; font-weight: 700; }
        .section-title { font-weight: 700; margin-bottom: 2rem; position: relative; padding-bottom: 0.75rem; }
        .section-title::after { content: ''; position: absolute; bottom: 0; left: 0; width: 60px; height: 3px; background: var(--primary); }
        .product-card { border: none; border-radius: 0.75rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; overflow: hidden; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .product-card .card-img-top { height: 200px; object-fit: cover; background: #f8f9fa; }
        .brand-card { text-align: center; padding: 1.5rem; border-radius: 0.75rem; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: transform 0.2s; }
        .brand-card:hover { transform: translateY(-2px); }
        .category-card { background: #fff; border-radius: 0.75rem; padding: 2rem 1rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: all 0.2s; text-decoration: none; color: inherit; display: block; }
        .category-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); color: var(--primary); }
        .category-card i { font-size: 2.5rem; color: var(--primary); }
        .price { font-size: 1.25rem; font-weight: 700; color: var(--primary); }
        .price del { font-size: 0.9rem; color: #94a3b8; font-weight: 400; }
        .testimonial-card { background: #fff; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06); height: 100%; }
        .cta-section { background: linear-gradient(135deg, #1a56db, #1e40af); color: #fff; padding: 4rem 0; }
        footer { background: var(--dark); color: rgba(255,255,255,0.7); padding: 3rem 0 1rem; }
        footer a { color: rgba(255,255,255,0.7); text-decoration: none; }
        footer a:hover { color: #fff; }
        .floating-btn { position: fixed; bottom: 20px; right: 20px; z-index: 999; }
        .floating-btn a { display: flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 50%; color: #fff; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .btn-whatsapp { background: #25d366; }
        .btn-call { background: var(--primary); bottom: 85px; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}"><i class="bi bi-printer"></i> {{ $settings['site_name'] ?? 'Cutting Plotter India' }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if($headerMenu && $headerMenu->items->count())
                        @foreach($headerMenu->items as $item)
                        <li class="nav-item {{ $item->children->count() ? 'dropdown' : '' }}">
                            @if($item->children->count())
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ $item->title }}</a>
                            <ul class="dropdown-menu">
                                @foreach($item->children as $child)
                                <li><a class="dropdown-item" href="{{ $child->url }}" @if($child->target === '_blank') target="_blank" @endif>{{ $child->title }}</a></li>
                                @endforeach
                            </ul>
                            @else
                            <a class="nav-link" href="{{ $item->url ?? '#' }}" @if($item->target === '_blank') target="_blank" @endif>
                                @if($item->icon)<i class="bi {{ $item->icon }}"></i> @endif{{ $item->title }}
                            </a>
                            @endif
                        </li>
                        @endforeach
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('brands.index') }}">Brands</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('blog.index') }}">Blog</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                    @endif
                    @auth
                        @if(auth()->user()->hasAnyRole(['super-admin','admin','content-manager']))
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-gear"></i> Admin</a></li>
                        @endif
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-0 rounded-0" role="alert">
        <div class="container">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    </div>
    @endif

    @yield('content')

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white">{{ $settings['site_name'] ?? 'Cutting Plotter India' }}</h5>
                    <p>{{ $settings['site_tagline'] ?? '' }}</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="text-white">Quick Links</h6>
                    <ul class="list-unstyled">
                        @if($footerMenu && $footerMenu->items->count())
                            @foreach($footerMenu->items as $item)
                            <li><a href="{{ $item->url }}">{{ $item->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('products.index') }}">Products</a></li>
                            <li><a href="{{ route('brands.index') }}">Brands</a></li>
                            <li><a href="{{ route('blog.index') }}">Blog</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="text-white">Contact</h6>
                    <p class="mb-1"><i class="bi bi-telephone"></i> {{ $settings['company_phone'] ?? '' }}</p>
                    <p class="mb-1"><i class="bi bi-envelope"></i> {{ $settings['company_email'] ?? '' }}</p>
                    <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $settings['company_address'] ?? '' }}</p>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="text-center mb-0 small">&copy; {{ date('Y') }} {{ $settings['company_name'] ?? 'Cutting Plotter India' }}. All rights reserved.</p>
        </div>
    </footer>

    <div class="floating-btn">
        @if(!empty($settings['company_whatsapp']))
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['company_whatsapp']) }}" class="btn-whatsapp mb-2" target="_blank" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
        @endif
        @if(!empty($settings['company_phone']))
        <a href="tel:{{ $settings['company_phone'] }}" class="btn-call" title="Call Us"><i class="bi bi-telephone-fill"></i></a>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
