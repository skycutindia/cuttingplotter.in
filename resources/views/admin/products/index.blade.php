@extends('admin.layouts.app')
@section('title', 'Products')
@section('page-title', 'Product Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search products..." value="{{ request('search') }}">
        <button class="btn btn-sm btn-outline-primary">Search</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Product</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Product</th><th>SKU</th><th>Brand</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($products as $product)
                <tr>
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
@endsection
