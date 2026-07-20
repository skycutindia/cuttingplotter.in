@extends('admin.layouts.app')
@section('title', 'Edit Menu')
@section('page-title', 'Menu: '.$menu->name)

@push('styles')
<style>
    .menu-item { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.75rem; margin-bottom: 0.5rem; }
    .menu-item .handle { cursor: grab; color: #94a3b8; }
    .menu-children { margin-left: 2rem; border-left: 2px solid #e2e8f0; padding-left: 1rem; }
</style>
@endpush

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0">Add Menu Item</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.menus.items.store', $menu) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="text" name="url" class="form-control form-control-sm" placeholder="/products or https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Item</label>
                        <select name="parent_id" class="form-select form-select-sm">
                            <option value="">Top Level</option>
                            @foreach($parentItems as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Icon (Bootstrap)</label>
                            <input type="text" name="icon" class="form-control form-control-sm" placeholder="bi-house">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Target</label>
                            <select name="target" class="form-select form-select-sm">
                                <option value="_self">Same Tab</option>
                                <option value="_blank">New Tab</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Add Item</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0">Menu Items</h6></div>
            <div class="card-body" id="menu-list">
                @php
                    $topItems = $menu->allItems->whereNull('parent_id');
                @endphp
                @forelse($topItems as $item)
                    @include('admin.menus.partials.item', ['item' => $item, 'menu' => $menu, 'allItems' => $menu->allItems])
                @empty
                    <p class="text-muted text-center py-4">No menu items yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@foreach($menu->allItems as $item)
<div class="modal fade" id="editItem{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.menus.items.update', [$menu, $item]) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit: {{ $item->title }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Title</label><input type="text" name="title" class="form-control" value="{{ $item->title }}" required></div>
                    <div class="mb-3"><label class="form-label">URL</label><input type="text" name="url" class="form-control" value="{{ $item->url }}"></div>
                    <div class="mb-3"><label class="form-label">Icon</label><input type="text" name="icon" class="form-control" value="{{ $item->icon }}"></div>
                    <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked($item->is_active)><label class="form-check-label">Active</label></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
