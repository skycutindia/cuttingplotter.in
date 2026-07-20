@extends('admin.layouts.app')
@section('title', 'Categories')
@section('page-title', 'Category Management')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Category</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Category</th><th>Parent</th><th>Products</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td>{{ $category->parent?->name ?? '-' }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td><span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $categories->links() }}</div>
</div>
@endsection
