@php
    use App\Models\Product;
    $limit = (int) ($content['limit'] ?? 8);
    $query = Product::with(['brand', 'images'])->where('is_active', true)->where('is_approved', true);
    $products = match($content['source'] ?? 'featured') {
        'latest' => $query->latest()->limit($limit)->get(),
        'trending' => $query->where('is_trending', true)->limit($limit)->get(),
        default => $query->where('is_featured', true)->orderBy('sort_order')->limit($limit)->get(),
    };
@endphp
@if($products->count())
<section class="py-5 bg-light">
    <div class="container">
        @if(!empty($content['title']))<h2 class="section-title">{{ $content['title'] }}</h2>@endif
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:180px"><i class="bi bi-box-seam text-muted" style="font-size:2.5rem"></i></div>
                    <div class="card-body">
                        <small class="text-muted">{{ $product->brand?->name }}</small>
                        <h6 class="mt-1"><a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a></h6>
                        @if($product->effective_price)<div class="price">₹{{ number_format($product->effective_price) }}</div>@endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
