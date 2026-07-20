@php
    use App\Models\Brand;
    $brands = Brand::where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->limit((int)($content['limit'] ?? 6))->get();
@endphp
@if($brands->count())
<section class="py-5">
    <div class="container">
        @if(!empty($content['title']))<h2 class="section-title">{{ $content['title'] }}</h2>@endif
        <div class="row g-4">
            @foreach($brands as $brand)
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('brands.show', $brand->slug) }}" class="brand-card d-block text-decoration-none text-dark text-center">
                    <h6 class="mb-0">{{ $brand->name }}</h6>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
