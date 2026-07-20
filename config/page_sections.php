<?php

return [
    'types' => [
        'hero_slider' => [
            'label' => 'Hero Slider',
            'icon' => 'bi-images',
            'fields' => ['slides'],
            'repeatable' => 'slides',
            'slide_fields' => ['heading', 'subheading', 'image', 'button_text', 'button_url'],
        ],
        'banner' => [
            'label' => 'Banner',
            'icon' => 'bi-card-image',
            'fields' => ['image', 'mobile_image', 'link', 'alt_text'],
        ],
        'heading' => [
            'label' => 'Heading',
            'icon' => 'bi-type-h1',
            'fields' => ['text', 'tag', 'alignment', 'subtitle'],
        ],
        'rich_text' => [
            'label' => 'Rich Text',
            'icon' => 'bi-text-paragraph',
            'fields' => ['body', 'container_width'],
        ],
        'image' => [
            'label' => 'Image',
            'icon' => 'bi-image',
            'fields' => ['image', 'alt_text', 'caption', 'link', 'alignment'],
        ],
        'video' => [
            'label' => 'Video',
            'icon' => 'bi-play-btn',
            'fields' => ['video_url', 'poster', 'title'],
        ],
        'button' => [
            'label' => 'Button / CTA',
            'icon' => 'bi-cursor',
            'fields' => ['text', 'url', 'style', 'alignment', 'open_new_tab'],
        ],
        'product_slider' => [
            'label' => 'Product Slider',
            'icon' => 'bi-box-seam',
            'fields' => ['title', 'source', 'limit', 'category_id', 'brand_id'],
        ],
        'brand_slider' => [
            'label' => 'Brand Slider',
            'icon' => 'bi-tags',
            'fields' => ['title', 'limit'],
        ],
        'category_slider' => [
            'label' => 'Category Slider',
            'icon' => 'bi-folder',
            'fields' => ['title', 'limit'],
        ],
        'counter' => [
            'label' => 'Counter',
            'icon' => 'bi-123',
            'fields' => ['items'],
            'repeatable' => 'items',
            'item_fields' => ['number', 'suffix', 'label'],
        ],
        'faq' => [
            'label' => 'FAQ',
            'icon' => 'bi-question-circle',
            'fields' => ['title', 'items'],
            'repeatable' => 'items',
            'item_fields' => ['question', 'answer'],
        ],
        'testimonial' => [
            'label' => 'Testimonials',
            'icon' => 'bi-chat-quote',
            'fields' => ['title', 'source'],
        ],
        'gallery' => [
            'label' => 'Gallery',
            'icon' => 'bi-grid-3x3-gap',
            'fields' => ['title', 'columns', 'images'],
            'repeatable' => 'images',
            'image_fields' => ['image', 'caption', 'link'],
        ],
        'contact_form' => [
            'label' => 'Contact Form',
            'icon' => 'bi-envelope',
            'fields' => ['title', 'subtitle', 'form_type'],
        ],
        'newsletter' => [
            'label' => 'Newsletter',
            'icon' => 'bi-mailbox',
            'fields' => ['title', 'subtitle', 'button_text'],
        ],
        'google_map' => [
            'label' => 'Google Map',
            'icon' => 'bi-geo-alt',
            'fields' => ['embed_url', 'height'],
        ],
        'html_block' => [
            'label' => 'HTML Block',
            'icon' => 'bi-code-slash',
            'fields' => ['html'],
        ],
        'spacer' => [
            'label' => 'Spacer',
            'icon' => 'bi-distribute-vertical',
            'fields' => ['height'],
        ],
    ],
];
