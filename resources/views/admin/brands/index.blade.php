@extends('admin.layouts.app')
@section('title', 'Brands')
@section('page-title', 'Brand Management')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Brand</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Brand</th><th>Products</th><th>Featured</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($brands as $brand)
                <tr>
                    <td><strong>{{ $brand->name }}</strong></td>
                    <td>{{ $brand->products_count }}</td>
                    <td>@if($brand->is_featured)<span class="badge bg-warning text-dark">Yes</span>@else - @endif</td>
                    <td><span class="badge bg-{{ $brand->is_active ? 'success' : 'secondary' }}">{{ $brand->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $brands->links() }}</div>
</div>
@endsection
