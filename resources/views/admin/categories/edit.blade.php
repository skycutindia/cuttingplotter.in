@extends('admin.layouts.app')
@section('title', 'Edit Category')
@section('page-title', 'Edit: '.$category->name)

@section('content')
<div class="row justify-content-center"><div class="col-md-8">
<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
<div class="card"><div class="card-body">
    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required></div>
    <div class="mb-3"><label class="form-label">Parent Category</label><select name="parent_id" class="form-select"><option value="">None</option>@foreach($parents as $p)<option value="{{ $p->id }}" @selected($category->parent_id == $p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea></div>
    <div class="mb-3"><label class="form-label">Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
    <div class="form-check mb-2"><input type="checkbox" name="is_featured" value="1" class="form-check-input" id="featured" @checked($category->is_featured)><label for="featured" class="form-check-label">Featured</label></div>
    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" @checked($category->is_active)><label for="active" class="form-check-label">Active</label></div>
    <button type="submit" class="btn btn-primary">Update Category</button>
</div></div>
</form></div></div>
@endsection
