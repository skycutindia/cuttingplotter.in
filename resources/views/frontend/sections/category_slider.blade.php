@php
    use App\Models\Category;
    $categories = Category::whereNull('parent_id')->where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->limit((int)($content['limit'] ?? 6))->get();
@endphp
@if($categories->count())
<section class="py-5 bg-light">
    <div class="container">
        @if(!empty($content['title']))<h2 class="section-title">{{ $content['title'] }}</h2>@endif
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
