@extends('frontend.layouts.app')
@section('title', 'Blog | '.($settings['site_name'] ?? ''))
@section('content')
<div class="bg-light py-4"><div class="container"><h1 class="h3 mb-0">Blog</h1></div></div>
<div class="container py-4">
    <div class="row g-4">
        @forelse($blogs as $blog)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">{{ $blog->category?->name }} | {{ $blog->published_at?->format('d M Y') }}</small>
                    <h5 class="card-title mt-2"><a href="{{ route('blog.show', $blog->slug) }}" class="text-decoration-none">{{ $blog->title }}</a></h5>
                    <p class="card-text text-muted">{{ $blog->excerpt }}</p>
                    <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-outline-primary btn-sm">Read More</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">No blog posts yet.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $blogs->links() }}</div>
</div>
@endsection
