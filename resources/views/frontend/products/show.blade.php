@extends('frontend.layouts.app')

@section('title', ($product->meta_title ?? $product->name).' | '.($settings['site_name'] ?? ''))
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
<div class="bg-light py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                @if($product->category)<li class="breadcrumb-item">{{ $product->category->name }}</li>@endif
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:400px">
                <i class="bi bi-box-seam text-muted" style="font-size:5rem"></i>
            </div>
        </div>
        <div class="col-lg-6">
            <small class="text-muted">{{ $product->brand?->name }} | SKU: {{ $product->sku }}</small>
            <h1 class="h2 mt-1">{{ $product->name }}</h1>
            <div class="price my-3">
                @if($product->offer_price)
                    <del class="text-muted">₹{{ number_format($product->price) }}</del>
                    <span class="fs-3">₹{{ number_format($product->offer_price) }}</span>
                @elseif($product->price)
                    <span class="fs-3">₹{{ number_format($product->price) }}</span>
                @else
                    <span class="text-muted">Contact for Price</span>
                @endif
            </div>
            @if($product->short_description)<p>{{ $product->short_description }}</p>@endif
            @if($product->stock > 0)<p class="text-success"><i class="bi bi-check-circle"></i> In Stock ({{ $product->stock }} units)</p>@endif
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">Request Quote</a>
                @if(!empty($settings['company_whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['company_whatsapp']) }}?text=Hi, I'm interested in {{ urlencode($product->name) }}" class="btn btn-success btn-lg" target="_blank"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                @endif
            </div>
            @if($product->features)
            <div class="mt-4">
                <h6>Key Features</h6>
                <ul class="list-unstyled">@foreach($product->features as $feature)<li><i class="bi bi-check2 text-primary"></i> {{ $feature }}</li>@endforeach</ul>
            </div>
            @endif
        </div>
    </div>

    @if($product->description)
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#description">Description</a></li>
                @if($product->specifications->count())<li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#specs">Specifications</a></li>@endif
            </ul>
            <div class="tab-content border border-top-0 p-4">
                <div class="tab-pane fade show active" id="description">{!! $product->description !!}</div>
                @if($product->specifications->count())
                <div class="tab-pane fade" id="specs">
                    <table class="table table-striped">
                        @foreach($product->specifications as $spec)
                        <tr><td class="fw-bold" style="width:40%">{{ $spec->label }}</td><td>{{ $spec->value }}</td></tr>
                        @endforeach
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
