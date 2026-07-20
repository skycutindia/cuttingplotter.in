@extends('frontend.layouts.app')
@section('title', 'Brands | '.($settings['site_name'] ?? ''))
@section('content')
<div class="bg-light py-4"><div class="container"><h1 class="h3 mb-0">Our Brands</h1></div></div>
<div class="container py-4">
    <div class="row g-4">
        @foreach($brands as $brand)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('brands.show', $brand->slug) }}" class="brand-card d-block text-decoration-none text-dark">
                <h5>{{ $brand->name }}</h5>
                <p class="small text-muted mb-0">{{ $brand->products_count }} products</p>
            </a>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $brands->links() }}</div>
</div>
@endsection
