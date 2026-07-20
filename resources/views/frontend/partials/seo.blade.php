@php
    $pageTitle = trim($__env->yieldContent('title', $settings['default_meta_title'] ?? 'Cutting Plotter India'));
    $pageDescription = trim($__env->yieldContent('meta_description', $settings['default_meta_description'] ?? ''));
    $canonical = trim($__env->yieldContent('canonical', url()->current()));
    $ogImage = trim($__env->yieldContent('og_image', $settings['og_image'] ?? ''));
@endphp
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
@hasSection('meta_keywords')
<meta name="keywords" content="@yield('meta_keywords')">
@elseif(!empty($settings['default_meta_keywords']))
<meta name="keywords" content="{{ $settings['default_meta_keywords'] }}">
@endif
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:site_name" content="{{ $settings['site_name'] ?? 'Cutting Plotter India' }}">
@if($ogImage)<meta property="og:image" content="{{ $ogImage }}">@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
@if(!empty($settings['twitter_handle']))<meta name="twitter:site" content="{{ $settings['twitter_handle'] }}">@endif
@if($ogImage)<meta name="twitter:image" content="{{ $ogImage }}">@endif

@stack('schema')
