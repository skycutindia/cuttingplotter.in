@if(!empty($content['image']))
<section class="py-4">
    <div class="container text-{{ $content['alignment'] ?? 'center' }}">
        @if(!empty($content['link']))<a href="{{ $content['link'] }}">@endif
            <img src="{{ $content['image'] }}" alt="{{ $content['alt_text'] ?? '' }}" class="img-fluid rounded">
        @if(!empty($content['link']))</a>@endif
        @if(!empty($content['caption']))<p class="text-muted small mt-2">{{ $content['caption'] }}</p>@endif
    </div>
</section>
@endif
