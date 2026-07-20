@extends('admin.layouts.app')
@section('title', 'Banners')
@section('page-title', 'Banner Manager')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <select name="location" class="form-select form-select-sm">
            <option value="">All Locations</option>
            @foreach($locations as $key => $label)
            <option value="{{ $key }}" @selected(request('location') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-sm btn-outline-primary">Filter</button>
    </form>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Banner</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Banner</th><th>Location</th><th>Schedule</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($banners as $banner)
                <tr>
                    <td>
                        <strong>{{ $banner->title }}</strong>
                        @if($banner->link)<br><small class="text-muted">{{ $banner->link }}</small>@endif
                    </td>
                    <td><span class="badge bg-light text-dark">{{ $locations[$banner->location] ?? $banner->location }}</span></td>
                    <td class="small">
                        @if($banner->starts_at) From: {{ $banner->starts_at->format('d M Y') }}<br>@endif
                        @if($banner->ends_at) Until: {{ $banner->ends_at->format('d M Y') }}@endif
                        @if(!$banner->starts_at && !$banner->ends_at) Always @endif
                    </td>
                    <td><span class="badge bg-{{ $banner->is_active ? 'success' : 'secondary' }}">{{ $banner->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $banners->withQueryString()->links() }}</div>
</div>
@endsection
