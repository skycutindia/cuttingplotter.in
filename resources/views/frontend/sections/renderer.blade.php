@php $c = $section->content ?? []; @endphp

@switch($section->type)
    @case('hero_slider')
        @include('frontend.sections.hero_slider', ['content' => $c])
        @break
    @case('heading')
        @include('frontend.sections.heading', ['content' => $c])
        @break
    @case('rich_text')
        @include('frontend.sections.rich_text', ['content' => $c])
        @break
    @case('image')
        @include('frontend.sections.image', ['content' => $c])
        @break
    @case('video')
        @include('frontend.sections.video', ['content' => $c])
        @break
    @case('button')
        @include('frontend.sections.button', ['content' => $c])
        @break
    @case('product_slider')
        @include('frontend.sections.product_slider', ['content' => $c])
        @break
    @case('brand_slider')
        @include('frontend.sections.brand_slider', ['content' => $c])
        @break
    @case('category_slider')
        @include('frontend.sections.category_slider', ['content' => $c])
        @break
    @case('counter')
        @include('frontend.sections.counter', ['content' => $c])
        @break
    @case('faq')
        @include('frontend.sections.faq', ['content' => $c])
        @break
    @case('testimonial')
        @include('frontend.sections.testimonial', ['content' => $c])
        @break
    @case('contact_form')
        @include('frontend.sections.contact_form', ['content' => $c])
        @break
    @case('newsletter')
        @include('frontend.sections.newsletter', ['content' => $c])
        @break
    @case('google_map')
        @include('frontend.sections.google_map', ['content' => $c])
        @break
    @case('html_block')
        @include('frontend.sections.html_block', ['content' => $c])
        @break
    @case('banner')
        @include('frontend.sections.banner', ['content' => $c])
        @break
    @case('spacer')
        <div style="height: {{ $c['height'] ?? 40 }}px"></div>
        @break
@endswitch
