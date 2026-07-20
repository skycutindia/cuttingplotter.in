@if(!empty($content['text']))
<section class="py-3">
    <div class="container text-{{ $content['alignment'] ?? 'center' }}">
        <a href="{{ $content['url'] ?? '#' }}" class="btn btn-{{ $content['style'] ?? 'primary' }} btn-lg" @if(!empty($content['open_new_tab'])) target="_blank" @endif>{{ $content['text'] }}</a>
    </div>
</section>
@endif
