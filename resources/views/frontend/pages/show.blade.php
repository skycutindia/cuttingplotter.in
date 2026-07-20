@extends('frontend.layouts.app')
@section('title', ($page->meta_title ?? $page->title))
@section('meta_description', $page->meta_description)

@section('content')
@php $activeSections = $page->sections->where('is_active', true); @endphp

@if($activeSections->count())
    @foreach($activeSections as $section)
        @include('frontend.sections.renderer', ['section' => $section])
    @endforeach
@else
    <div class="bg-light py-4">
        <div class="container"><h1 class="h3 mb-0">{{ $page->title }}</h1></div>
    </div>
    <div class="container py-4">{!! $page->content !!}</div>
@endif
@endsection
