@php
    use App\Models\Testimonial;
    $query = Testimonial::where('is_active', true);
    if (($content['source'] ?? 'featured') === 'featured') {
        $query->where('is_featured', true);
    }
    $testimonials = $query->orderBy('sort_order')->limit(6)->get();
@endphp
@if($testimonials->count())
<section class="py-5 bg-light">
    <div class="container">
        @if(!empty($content['title']))<h2 class="section-title">{{ $content['title'] }}</h2>@endif
        <div class="row g-4">
            @foreach($testimonials as $t)
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="mb-2">@for($i = 0; $i < $t->rating; $i++)<i class="bi bi-star-fill text-warning"></i>@endfor</div>
                    <p>"{{ $t->content }}"</p>
                    <strong>{{ $t->name }}</strong>
                    <small class="d-block text-muted">{{ $t->designation }}{{ $t->company ? ', '.$t->company : '' }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
