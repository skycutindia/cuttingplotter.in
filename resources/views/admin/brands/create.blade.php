@extends('admin.layouts.app')
@section('title', 'Add Brand')
@section('page-title', 'Add Brand')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Brand Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Short Description</label><input type="text" name="short_description" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"></textarea></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Logo</label><input type="file" name="logo" class="form-control" accept="image/*"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Banner</label><input type="file" name="banner" class="form-control" accept="image/*"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Meta Description</label><textarea name="meta_description" class="form-control" rows="2"></textarea></div>
                    <div class="form-check mb-2"><input type="checkbox" name="is_featured" value="1" class="form-check-input" id="featured"><label for="featured" class="form-check-label">Featured</label></div>
                    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" checked><label for="active" class="form-check-label">Active</label></div>
                    <button type="submit" class="btn btn-primary">Create Brand</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
