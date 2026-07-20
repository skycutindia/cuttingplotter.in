@php
    $content = $section->content ?? [];
    $prefix = "content";
@endphp

@if($type === 'hero_slider')
    <div id="slides-{{ $section->id }}">
        @php $slides = $content['slides'] ?? [['heading' => '', 'subheading' => '', 'button_text' => '', 'button_url' => '']]; @endphp
        @foreach($slides as $i => $slide)
        <div class="border rounded p-3 mb-2 slide-item">
            <div class="row g-2">
                <div class="col-md-6"><label class="form-label small">Heading</label><input type="text" name="content[slides][{{ $i }}][heading]" class="form-control form-control-sm" value="{{ $slide['heading'] ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label small">Subheading</label><input type="text" name="content[slides][{{ $i }}][subheading]" class="form-control form-control-sm" value="{{ $slide['subheading'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label small">Button Text</label><input type="text" name="content[slides][{{ $i }}][button_text]" class="form-control form-control-sm" value="{{ $slide['button_text'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label small">Button URL</label><input type="text" name="content[slides][{{ $i }}][button_url]" class="form-control form-control-sm" value="{{ $slide['button_url'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label small">Image URL</label><input type="text" name="content[slides][{{ $i }}][image]" class="form-control form-control-sm" value="{{ $slide['image'] ?? '' }}"></div>
            </div>
        </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addSlide({{ $section->id }})">+ Add Slide</button>

@elseif($type === 'heading')
    <div class="mb-3"><label class="form-label">Heading Text</label><input type="text" name="content[text]" class="form-control" value="{{ $content['text'] ?? '' }}"></div>
    <div class="mb-3"><label class="form-label">Subtitle</label><input type="text" name="content[subtitle]" class="form-control" value="{{ $content['subtitle'] ?? '' }}"></div>
    <div class="row">
        <div class="col-6 mb-3"><label class="form-label">Tag</label><select name="content[tag]" class="form-select"><option value="h1" @selected(($content['tag'] ?? 'h2') === 'h1')>H1</option><option value="h2" @selected(($content['tag'] ?? 'h2') === 'h2')>H2</option><option value="h3" @selected(($content['tag'] ?? '') === 'h3')>H3</option></select></div>
        <div class="col-6 mb-3"><label class="form-label">Alignment</label><select name="content[alignment]" class="form-select"><option value="left" @selected(($content['alignment'] ?? 'left') === 'left')>Left</option><option value="center" @selected(($content['alignment'] ?? '') === 'center')>Center</option><option value="right" @selected(($content['alignment'] ?? '') === 'right')>Right</option></select></div>
    </div>

@elseif($type === 'rich_text')
    <div class="mb-3"><label class="form-label">Content (HTML)</label><textarea name="content[body]" class="form-control" rows="8">{{ $content['body'] ?? '' }}</textarea></div>
    <div class="mb-3"><label class="form-label">Container Width</label><select name="content[container_width]" class="form-select"><option value="container" @selected(($content['container_width'] ?? 'container') === 'container')>Normal</option><option value="container-fluid" @selected(($content['container_width'] ?? '') === 'container-fluid')>Full Width</option></select></div>

@elseif($type === 'image')
    <div class="mb-3"><label class="form-label">Image URL</label><input type="text" name="content[image]" class="form-control" value="{{ $content['image'] ?? '' }}"></div>
    <div class="mb-3"><label class="form-label">Alt Text</label><input type="text" name="content[alt_text]" class="form-control" value="{{ $content['alt_text'] ?? '' }}"></div>
    <div class="mb-3"><label class="form-label">Caption</label><input type="text" name="content[caption]" class="form-control" value="{{ $content['caption'] ?? '' }}"></div>
    <div class="mb-3"><label class="form-label">Link URL</label><input type="text" name="content[link]" class="form-control" value="{{ $content['link'] ?? '' }}"></div>

@elseif($type === 'video')
    <div class="mb-3"><label class="form-label">Video URL (YouTube/Vimeo embed)</label><input type="text" name="content[video_url]" class="form-control" value="{{ $content['video_url'] ?? '' }}"></div>
    <div class="mb-3"><label class="form-label">Title</label><input type="text" name="content[title]" class="form-control" value="{{ $content['title'] ?? '' }}"></div>

@elseif($type === 'button')
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Button Text</label><input type="text" name="content[text]" class="form-control" value="{{ $content['text'] ?? '' }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">URL</label><input type="text" name="content[url]" class="form-control" value="{{ $content['url'] ?? '' }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Style</label><select name="content[style]" class="form-select"><option value="primary" @selected(($content['style'] ?? 'primary') === 'primary')>Primary</option><option value="outline-primary" @selected(($content['style'] ?? '') === 'outline-primary')>Outline</option><option value="success" @selected(($content['style'] ?? '') === 'success')>Success</option></select></div>
        <div class="col-md-6 mb-3"><label class="form-label">Alignment</label><select name="content[alignment]" class="form-select"><option value="left" @selected(($content['alignment'] ?? 'center') === 'left')>Left</option><option value="center" @selected(($content['alignment'] ?? 'center') === 'center')>Center</option><option value="right" @selected(($content['alignment'] ?? '') === 'right')>Right</option></select></div>
    </div>

@elseif(in_array($type, ['product_slider', 'brand_slider', 'category_slider']))
    <div class="mb-3"><label class="form-label">Section Title</label><input type="text" name="content[title]" class="form-control" value="{{ $content['title'] ?? '' }}"></div>
    <div class="mb-3"><label class="form-label">Number of Items</label><input type="number" name="content[limit]" class="form-control" value="{{ $content['limit'] ?? 8 }}" min="1" max="24"></div>
    @if($type === 'product_slider')
    <div class="mb-3"><label class="form-label">Source</label><select name="content[source]" class="form-select"><option value="featured" @selected(($content['source'] ?? 'featured') === 'featured')>Featured</option><option value="latest" @selected(($content['source'] ?? '') === 'latest')>Latest</option><option value="trending" @selected(($content['source'] ?? '') === 'trending')>Trending</option></select></div>
    @endif

@elseif($type === 'counter')
    @php $items = $content['items'] ?? [['number' => '', 'suffix' => '+', 'label' => '']]; @endphp
    @foreach($items as $i => $item)
    <div class="row g-2 mb-2">
        <div class="col-4"><input type="text" name="content[items][{{ $i }}][number]" class="form-control form-control-sm" placeholder="Number" value="{{ $item['number'] ?? '' }}"></div>
        <div class="col-2"><input type="text" name="content[items][{{ $i }}][suffix]" class="form-control form-control-sm" placeholder="+" value="{{ $item['suffix'] ?? '' }}"></div>
        <div class="col-6"><input type="text" name="content[items][{{ $i }}][label]" class="form-control form-control-sm" placeholder="Label" value="{{ $item['label'] ?? '' }}"></div>
    </div>
    @endforeach

@elseif($type === 'faq')
    <div class="mb-3"><label class="form-label">Section Title</label><input type="text" name="content[title]" class="form-control" value="{{ $content['title'] ?? 'Frequently Asked Questions' }}"></div>
    @php $items = $content['items'] ?? [['question' => '', 'answer' => '']]; @endphp
    @foreach($items as $i => $item)
    <div class="border rounded p-2 mb-2">
        <input type="text" name="content[items][{{ $i }}][question]" class="form-control form-control-sm mb-1" placeholder="Question" value="{{ $item['question'] ?? '' }}">
        <textarea name="content[items][{{ $i }}][answer]" class="form-control form-control-sm" rows="2" placeholder="Answer">{{ $item['answer'] ?? '' }}</textarea>
    </div>
    @endforeach

@elseif($type === 'testimonial')
    <div class="mb-3"><label class="form-label">Section Title</label><input type="text" name="content[title]" class="form-control" value="{{ $content['title'] ?? 'What Our Customers Say' }}"></div>
    <div class="mb-3"><label class="form-label">Source</label><select name="content[source]" class="form-select"><option value="featured" @selected(($content['source'] ?? 'featured') === 'featured')>Featured Testimonials</option><option value="all" @selected(($content['source'] ?? '') === 'all')>All Active</option></select></div>

@elseif($type === 'contact_form')
    <div class="mb-3"><label class="form-label">Title</label><input type="text" name="content[title]" class="form-control" value="{{ $content['title'] ?? 'Contact Us' }}"></div>
    <div class="mb-3"><label class="form-label">Subtitle</label><input type="text" name="content[subtitle]" class="form-control" value="{{ $content['subtitle'] ?? '' }}"></div>

@elseif($type === 'newsletter')
    <div class="mb-3"><label class="form-label">Title</label><input type="text" name="content[title]" class="form-control" value="{{ $content['title'] ?? 'Subscribe to Our Newsletter' }}"></div>
    <div class="mb-3"><label class="form-label">Subtitle</label><input type="text" name="content[subtitle]" class="form-control" value="{{ $content['subtitle'] ?? '' }}"></div>
    <div class="mb-3"><label class="form-label">Button Text</label><input type="text" name="content[button_text]" class="form-control" value="{{ $content['button_text'] ?? 'Subscribe' }}"></div>

@elseif($type === 'google_map')
    <div class="mb-3"><label class="form-label">Google Maps Embed URL</label><input type="text" name="content[embed_url]" class="form-control" value="{{ $content['embed_url'] ?? '' }}"></div>
    <div class="mb-3"><label class="form-label">Height (px)</label><input type="number" name="content[height]" class="form-control" value="{{ $content['height'] ?? 400 }}"></div>

@elseif($type === 'html_block')
    <div class="mb-3"><label class="form-label">Custom HTML</label><textarea name="content[html]" class="form-control font-monospace" rows="10">{{ $content['html'] ?? '' }}</textarea></div>

@elseif($type === 'banner')
    <div class="mb-3"><label class="form-label">Image URL</label><input type="text" name="content[image]" class="form-control" value="{{ $content['image'] ?? '' }}"></div>
    <div class="mb-3"><label class="form-label">Link URL</label><input type="text" name="content[link]" class="form-control" value="{{ $content['link'] ?? '' }}"></div>

@elseif($type === 'spacer')
    <div class="mb-3"><label class="form-label">Height (px)</label><input type="number" name="content[height]" class="form-control" value="{{ $content['height'] ?? 40 }}" min="10" max="200"></div>

@else
    <p class="text-muted">Configure this section using the fields below.</p>
    <div class="mb-3"><label class="form-label">Content (JSON)</label><textarea name="content[raw]" class="form-control font-monospace" rows="4">{{ json_encode($content, JSON_PRETTY_PRINT) }}</textarea></div>
@endif
