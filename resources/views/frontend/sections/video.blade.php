@if(!empty($content['video_url']))
<section class="py-4">
    <div class="container">
        @if(!empty($content['title']))<h3 class="mb-3">{{ $content['title'] }}</h3>@endif
        <div class="ratio ratio-16x9">
            <iframe src="{{ $content['video_url'] }}" allowfullscreen></iframe>
        </div>
    </div>
</section>
@endif
