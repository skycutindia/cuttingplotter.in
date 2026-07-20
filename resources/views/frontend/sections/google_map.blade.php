@if(!empty($content['embed_url']))
<section class="py-0">
    <iframe src="{{ $content['embed_url'] }}" width="100%" height="{{ $content['height'] ?? 400 }}" style="border:0;" allowfullscreen loading="lazy"></iframe>
</section>
@endif
