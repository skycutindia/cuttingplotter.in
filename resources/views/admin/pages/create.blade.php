@extends('admin.layouts.app')
@section('title', 'New Page')
@section('page-title', 'Create Page')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header bg-white"><h6 class="mb-0">Page Details</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Page Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. About Us">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="auto-generated from title">
                        <small class="text-muted">Will be accessible at /page/your-slug</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fallback Content</label>
                        <textarea name="content" class="form-control" rows="4" placeholder="Optional HTML shown if no sections are added">{{ old('content') }}</textarea>
                    </div>
                    <hr>
                    <h6 class="mb-3">SEO</h6>
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description') }}</textarea>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" checked>
                        <label class="form-check-label" for="active">Publish (Active)</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_homepage" value="1" class="form-check-input" id="homepage">
                        <label class="form-check-label" for="homepage">Set as Homepage</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Create & Add Sections</button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
