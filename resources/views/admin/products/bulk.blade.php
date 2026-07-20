@extends('admin.layouts.app')
@section('title', 'Bulk Products')
@section('page-title', 'Bulk Import / Export')

@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-upload"></i> Import Products</h6></div>
            <div class="card-body">
                <p class="text-muted small">Upload an Excel (.xlsx) or CSV file. Matching SKUs will be updated; new SKUs will be created. Brands and categories are created automatically if missing.</p>
                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-upload"></i> Import</button>
                        <a href="{{ route('admin.products.template') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download"></i> Download Template</a>
                    </div>
                </form>
                <hr>
                <h6 class="small fw-bold">Required columns</h6>
                <p class="small text-muted mb-0"><code>SKU</code>, <code>Name</code> — Optional: Product Code, Brand, Category, Price, Offer Price, Stock, HSN, GST Rate, SEO fields, flags (0/1)</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-download"></i> Export Products</h6></div>
            <div class="card-body">
                <p class="text-muted small">Export products to Excel. Optionally filter by brand, category, or status.</p>
                <form action="{{ route('admin.products.export') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label small">Brand</label>
                        <select name="brand_id" class="form-select form-select-sm">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)<option value="{{ $brand->id }}">{{ $brand->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Category</label>
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Status</label>
                        <select name="is_active" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel"></i> Export Excel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-check2-square"></i> Bulk Actions</h6></div>
    <div class="card-body">
        <p class="text-muted small">Select products from the product list, or manage them here by applying actions to filtered sets.</p>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary btn-sm">Go to Product List →</a>
    </div>
</div>
@endsection
