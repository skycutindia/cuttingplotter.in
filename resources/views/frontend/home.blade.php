@extends('frontend.layouts.app')

@section('title', $settings['site_name'] ?? 'Cutting Plotter India')

@section('content')
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1>{{ $settings['site_tagline'] ?? 'Premium Plotters, Printers & Industrial Printing Equipment' }}</h1>
                <p class="lead mt-3 opacity-75">India's trusted supplier of cutting plotters, UV printers, printheads, inks, and spare parts from top global brands.</p>
                <div class="mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg me-2">Browse Products</a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">Get a Quote</a>
                </div>
            </div>
        </div>
    </div>
</section>

@if($categories->count())
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Product Categories</h2>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-card">
                    <i class="bi bi-grid d-block mb-2"></i>
                    <h6 class="mb-0">{{ $category->name }}</h6>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($featuredProducts->count())
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:200px">
                        <i class="bi bi-box-seam text-muted" style="font-size:3rem"></i>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">{{ $product->brand?->name }}</small>
                        <h6 class="card-title mt-1"><a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a></h6>
                        <div class="price">
                            @if($product->offer_price)
                                <del>₹{{ number_format($product->price) }}</del> ₹{{ number_format($product->offer_price) }}
                            @elseif($product->price)
                                ₹{{ number_format($product->price) }}
                            @else
                                <span class="text-muted small">Contact for Price</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm w-100">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($brands->count())
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Our Brands</h2>
        <div class="row g-4">
            @foreach($brands as $brand)
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('brands.show', $brand->slug) }}" class="brand-card d-block text-decoration-none text-dark">
                    <h6 class="mb-0">{{ $brand->name }}</h6>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($testimonials->count())
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">What Our Customers Say</h2>
        <div class="row g-4">
            @foreach($testimonials as $testimonial)
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="mb-2">@for($i = 0; $i < $testimonial->rating; $i++)<i class="bi bi-star-fill text-warning"></i>@endfor</div>
                    <p class="mb-3">"{{ $testimonial->content }}"</p>
                    <strong>{{ $testimonial->name }}</strong>
                    <small class="d-block text-muted">{{ $testimonial->designation }}, {{ $testimonial->company }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="cta-section text-center">
    <div class="container">
        <h2 class="mb-3">Need Help Choosing the Right Equipment?</h2>
        <p class="lead opacity-75 mb-4">Our experts are ready to help you find the perfect plotter or printer for your business.</p>
        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">Request a Free Quote</a>
    </div>
</section>
@endsection
