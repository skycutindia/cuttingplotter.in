@extends('admin.layouts.app')
@section('title', 'Add Category')
@section('page-title', 'Add Category')

@section('content')
<div class="row justify-content-center"><div class="col-md-8">
<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">@csrf
<div class="card"><div class="card-body">
    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Parent Category</label><select name="parent_id" class="form-select"><option value="">None (Top Level)</option>@foreach($parents as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></div>
    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
    <div class="mb-3"><label class="form-label">Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
    <div class="form-check mb-2"><input type="checkbox" name="is_featured" value="1" class="form-check-input" id="featured"><label for="featured" class="form-check-label">Featured</label></div>
    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" checked><label for="active" class="form-check-label">Active</label></div>
    <button type="submit" class="btn btn-primary">Create Category</button>
</div></div>
</form></div></div>
@endsection
