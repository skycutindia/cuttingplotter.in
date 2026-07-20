@if(!empty($content['items']))
<section class="py-5">
    <div class="container">
        @if(!empty($content['title']))<h2 class="section-title">{{ $content['title'] }}</h2>@endif
        <div class="accordion" id="faqAccordion">
            @foreach($content['items'] as $i => $item)
            @if(!empty($item['question']))
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">{{ $item['question'] }}</button>
                </h2>
                <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">{{ $item['answer'] ?? '' }}</div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif
