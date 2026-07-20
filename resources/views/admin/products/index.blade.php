@extends('admin.layouts.app')
@section('title', 'Products')
@section('page-title', 'Product Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search products..." value="{{ request('search') }}">
        <button class="btn btn-sm btn-outline-primary">Search</button>
    </form>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.bulk') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-file-earmark-excel"></i> Import / Export</a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Product</a>
    </div>
</div>

<form id="bulk-form" method="POST">
    @csrf
    <div class="card mb-2">
        <div class="card-body py-2 d-flex flex-wrap gap-2 align-items-center">
            <span class="small text-muted me-2">Bulk:</span>
            <button type="submit" formaction="{{ route('admin.products.bulk-update') }}" name="action" value="activate" class="btn btn-sm btn-outline-success" onclick="return requireSelection()">Activate</button>
            <button type="submit" formaction="{{ route('admin.products.bulk-update') }}" name="action" value="deactivate" class="btn btn-sm btn-outline-secondary" onclick="return requireSelection()">Deactivate</button>
            <button type="submit" formaction="{{ route('admin.products.bulk-update') }}" name="action" value="feature" class="btn btn-sm btn-outline-warning" onclick="return requireSelection()">Feature</button>
            <button type="submit" formaction="{{ route('admin.products.bulk-update') }}" name="action" value="unfeature" class="btn btn-sm btn-outline-warning" onclick="return requireSelection()">Unfeature</button>
            <button type="submit" formaction="{{ route('admin.products.export-selected') }}" class="btn btn-sm btn-outline-success" onclick="return requireSelection()"><i class="bi bi-download"></i> Export</button>
            <button type="submit" formaction="{{ route('admin.products.bulk-update') }}" name="action" value="delete" class="btn btn-sm btn-outline-danger" onclick="return requireSelection() && confirm('Delete selected products?')">Delete</button>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:40px"><input type="checkbox" class="form-check-input" id="select-all"></th>
                        <th>Product</th><th>SKU</th><th>Brand</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ $product->id }}" class="form-check-input product-check"></td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->is_featured)<span class="badge bg-warning text-dark ms-1">Featured</span>@endif
                        </td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->brand?->name ?? '-' }}</td>
                        <td>{{ $product->category?->name ?? '-' }}</td>
                        <td>
                            @if($product->offer_price)
                                <del class="text-muted small">₹{{ number_format($product->price) }}</del>
                                ₹{{ number_format($product->offer_price) }}
                            @elseif($product->price)
                                ₹{{ number_format($product->price) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $product->stock }}</td>
                        <td><span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.products.clone', $product->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-outline-secondary"><i class="bi bi-copy"></i></button></form>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $products->links() }}</div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.product-check').forEach(c => c.checked = this.checked);
});
function requireSelection() {
    if (![...document.querySelectorAll('.product-check:checked')].length) {
        alert('Please select at least one product.');
        return false;
    }
    return true;
}
</script>
@endpush
