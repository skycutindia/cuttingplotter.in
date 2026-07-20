@php
    $tag = $content['tag'] ?? 'h2';
    $align = $content['alignment'] ?? 'left';
    $textAlign = match($align) { 'center' => 'text-center', 'right' => 'text-end', default => 'text-start' };
@endphp
<section class="py-4">
    <div class="container {{ $textAlign }}">
        @if(!empty($content['text']))<{!! $tag !!} class="section-title d-inline-block">{{ $content['text'] }}</{!! $tag !!}>@endif
        @if(!empty($content['subtitle']))<p class="text-muted lead">{{ $content['subtitle'] }}</p>@endif
    </div>
</section>
