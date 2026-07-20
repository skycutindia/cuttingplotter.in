@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3"><i class="bi bi-box-seam"></i></div>
                <div><h3 class="mb-0">{{ $stats['products'] }}</h3><small class="text-muted">Products</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success me-3"><i class="bi bi-tags"></i></div>
                <div><h3 class="mb-0">{{ $stats['brands'] }}</h3><small class="text-muted">Brands</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3"><i class="bi bi-people"></i></div>
                <div><h3 class="mb-0">{{ $stats['new_leads'] }}</h3><small class="text-muted">New Leads</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger me-3"><i class="bi bi-exclamation-triangle"></i></div>
                <div><h3 class="mb-0">{{ $stats['low_stock'] }}</h3><small class="text-muted">Low Stock</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info me-3"><i class="bi bi-folder2-open"></i></div>
                <div><h3 class="mb-0">{{ $stats['media'] }}</h3><small class="text-muted">Media Files</small></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0">Recent Leads</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Name</th><th>Type</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($recentLeads as $lead)
                        <tr>
                            <td><a href="{{ route('admin.leads.show', $lead) }}">{{ $lead->name }}</a></td>
                            <td><span class="badge bg-secondary">{{ $lead->type }}</span></td>
                            <td><span class="badge bg-{{ $lead->status === 'new' ? 'primary' : 'info' }}">{{ $lead->status }}</span></td>
                            <td>{{ $lead->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">No leads yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0">Most Viewed Products</h6></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($topProducts as $product)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ Str::limit($product->name, 30) }}</span>
                        <span class="badge bg-light text-dark">{{ $product->views }} views</span>
                    </li>
                    @empty
                    <li class="list-group-item text-muted text-center">No data</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
