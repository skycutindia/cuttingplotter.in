@extends('admin.layouts.app')
@section('title', 'Menus')
@section('page-title', 'Menu Builder')

@section('content')
<div class="row g-3">
    @foreach($menus as $menu)
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $menu->name }}</h5>
                <p class="text-muted small mb-2">Location: <code>{{ $menu->location }}</code></p>
                <p class="mb-3">{{ $menu->all_items_count }} items</p>
                <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i> Edit Menu</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
