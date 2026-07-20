@extends('frontend.layouts.app')

@section('title', 'Products | '.($settings['site_name'] ?? 'Cutting Plotter India'))

@section('content')
<div class="bg-light py-4">
    <div class="container">
        <h1 class="h3 mb-0">Our Products</h1>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-white"><h6 class="mb-0">Filters</h6></div>
                <div class="card-body">
                    <form method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search products...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Brand</label>
                            <select name="brand" class="form-select form-select-sm">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Category</label>
                            <select name="category" class="form-select form-select-sm">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Sort</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="latest" @selected(request('sort') === 'latest')>Latest</option>
                                <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                                <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                                <option value="name" @selected(request('sort') === 'name')>Name</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="card product-card h-100">
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:200px">
                            <i class="bi bi-box-seam text-muted" style="font-size:3rem"></i>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">{{ $product->brand?->name }}</small>
                            <h6 class="card-title mt-1"><a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a></h6>
                            <p class="small text-muted">{{ Str::limit($product->short_description, 80) }}</p>
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
                @empty
                <div class="col-12 text-center py-5"><p class="text-muted">No products found.</p></div>
                @endforelse
            </div>
            <div class="mt-4">{{ $products->withQueryString()->links() }}</div>
        </div>
    </div>
</div>
@endsection
