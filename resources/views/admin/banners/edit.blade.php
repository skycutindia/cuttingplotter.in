@extends('admin.layouts.app')
@section('title', 'Edit Banner')
@section('page-title', 'Edit: '.$banner->title)

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
<div class="card"><div class="card-body">
    <div class="mb-3"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="{{ $banner->title }}" required></div>
    <div class="mb-3"><label class="form-label">Location *</label><select name="location" class="form-select" required>@foreach($locations as $k => $v)<option value="{{ $k }}" @selected($banner->location === $k)>{{ $v }}</option>@endforeach</select></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Desktop Image</label><input type="file" name="image" class="form-control" accept="image/*">@if($banner->image)<small class="text-muted">Current: {{ $banner->image }}</small>@endif</div>
        <div class="col-md-6 mb-3"><label class="form-label">Mobile Image</label><input type="file" name="mobile_image" class="form-control" accept="image/*"></div>
    </div>
    <div class="mb-3"><label class="form-label">Link URL</label><input type="text" name="link" class="form-control" value="{{ $banner->link }}"></div>
    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ $banner->description }}</textarea></div>
    <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Start Date</label><input type="datetime-local" name="starts_at" class="form-control" value="{{ $banner->starts_at?->format('Y-m-d\TH:i') }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">End Date</label><input type="datetime-local" name="ends_at" class="form-control" value="{{ $banner->ends_at?->format('Y-m-d\TH:i') }}"></div>
    </div>
    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" @checked($banner->is_active)><label for="active" class="form-check-label">Active</label></div>
    <button type="submit" class="btn btn-primary">Update Banner</button>
</div></div>
</form></div></div>
@endsection
