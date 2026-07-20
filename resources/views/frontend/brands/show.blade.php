@extends('frontend.layouts.app')
@section('title', ($brand->meta_title ?? $brand->name).' | Brands')
@section('content')
<div class="bg-light py-4"><div class="container"><h1 class="h3 mb-0">{{ $brand->name }}</h1></div></div>
<div class="container py-4">
    @if($brand->description)<div class="mb-4">{!! $brand->description !!}</div>@endif
    <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" class="btn btn-primary">View {{ $brand->name }} Products</a>
</div>
@endsection
