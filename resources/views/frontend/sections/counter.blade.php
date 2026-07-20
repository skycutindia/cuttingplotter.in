@if(!empty($content['items']))
<section class="py-5" style="background: linear-gradient(135deg, #1e293b, #1a56db); color: #fff;">
    <div class="container">
        <div class="row text-center">
            @foreach($content['items'] as $item)
            <div class="col-6 col-md-3 mb-3">
                <div class="display-4 fw-bold">{{ $item['number'] ?? '0' }}{{ $item['suffix'] ?? '' }}</div>
                <div class="opacity-75">{{ $item['label'] ?? '' }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
