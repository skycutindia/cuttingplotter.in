@if(!empty($content['image']))
<section class="py-3">
    <div class="container-fluid px-0">
        @if(!empty($content['link']))<a href="{{ $content['link'] }}">@endif
            <img src="{{ $content['image'] }}" alt="{{ $content['alt_text'] ?? '' }}" class="w-100">
        @if(!empty($content['link']))</a>@endif
    </div>
</section>
@endif
