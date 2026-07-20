@extends('frontend.layouts.app')
@section('title', ($blog->meta_title ?? $blog->title).' | Blog')
@section('content')
<div class="container py-5">
    <article>
        <small class="text-muted">{{ $blog->category?->name }} | {{ $blog->published_at?->format('d M Y') }} | By {{ $blog->author?->name ?? 'Admin' }}</small>
        <h1 class="mt-2">{{ $blog->title }}</h1>
        <div class="mt-4">{!! $blog->content !!}</div>
    </article>
    <div class="mt-4"><a href="{{ route('blog.index') }}" class="btn btn-outline-primary btn-sm">&larr; Back to Blog</a></div>
</div>
@endsection
