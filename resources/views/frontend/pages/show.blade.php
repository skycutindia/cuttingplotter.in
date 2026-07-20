@extends('frontend.layouts.app')
@section('title', ($page->meta_title ?? $page->title))
@section('content')
<div class="bg-light py-4"><div class="container"><h1 class="h3 mb-0">{{ $page->title }}</h1></div></div>
<div class="container py-4">{!! $page->content !!}</div>
@endsection
