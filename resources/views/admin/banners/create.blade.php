@extends('admin.layouts.app')
@section('title', 'Add Banner')
@section('page-title', 'Add Banner')

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">@csrf
<div class="card"><div class="card-body">
    <div class="mb-3"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Location *</label><select name="location" class="form-select" required>@foreach($locations as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Desktop Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Mobile Image</label><input type="file" name="mobile_image" class="form-control" accept="image/*"></div>
    </div>
    <div class="mb-3"><label class="form-label">Link URL</label><input type="text" name="link" class="form-control" placeholder="/products"></div>
    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
    <div class="mb-3"><label class="form-label">Video URL (optional)</label><input type="url" name="video_url" class="form-control"></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Start Date</label><input type="datetime-local" name="starts_at" class="form-control"></div>
        <div class="col-md-6 mb-3"><label class="form-label">End Date</label><input type="datetime-local" name="ends_at" class="form-control"></div>
    </div>
    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" checked><label for="active" class="form-check-label">Active</label></div>
    <button type="submit" class="btn btn-primary">Create Banner</button>
</div></div>
</form></div></div>
@endsection
