@extends('admin.layouts.app')
@section('title', 'Edit Product')
@section('page-title', 'Edit: '.$product->name)

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Basic Information</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Product Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">SKU *</label><input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Product Code</label><input type="text" name="product_code" class="form-control" value="{{ old('product_code', $product->product_code) }}"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Short Description</label><textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="6">{{ old('description', $product->description) }}</textarea></div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">SEO</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title) }}"></div>
                    <div class="mb-3"><label class="form-label">Meta Description</label><textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $product->meta_description) }}</textarea></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Organization</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Brand</label><select name="brand_id" class="form-select"><option value="">Select Brand</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected($product->brand_id == $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
                    <div class="mb-3"><label class="form-label">Category</label><select name="category_id" class="form-select"><option value="">Select Category</option>@foreach($categories as $cat)<option value="{{ $cat->id }}" @selected($product->category_id == $cat->id)>{{ $cat->name }}</option>@endforeach</select></div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Pricing & Stock</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Price (₹)</label><input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $product->price) }}"></div>
                    <div class="mb-3"><label class="form-label">Offer Price (₹)</label><input type="number" name="offer_price" class="form-control" step="0.01" value="{{ old('offer_price', $product->offer_price) }}"></div>
                    <div class="mb-3"><label class="form-label">Stock</label><input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}"></div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Add Images</h6></div>
                <div class="card-body"><input type="file" name="images[]" class="form-control" multiple accept="image/*"></div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-check mb-2"><input type="checkbox" name="is_featured" value="1" class="form-check-input" id="featured" @checked($product->is_featured)><label class="form-check-label" for="featured">Featured</label></div>
                    <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" @checked($product->is_active)><label class="form-check-label" for="active">Active</label></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Product</button>
        </div>
    </div>
</form>
@endsection
