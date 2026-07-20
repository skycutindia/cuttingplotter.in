@extends('admin.layouts.app')
@section('title', 'Leads')
@section('page-title', 'Lead Management')

@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Type</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td><a href="{{ route('admin.leads.show', $lead) }}">{{ $lead->name }}</a></td>
                    <td>{{ $lead->email }}</td>
                    <td>{{ $lead->phone }}</td>
                    <td><span class="badge bg-secondary">{{ $lead->type }}</span></td>
                    <td><span class="badge bg-{{ $lead->status === 'new' ? 'primary' : 'info' }}">{{ $lead->status }}</span></td>
                    <td>{{ $lead->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('admin.leads.show', $lead) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $leads->links() }}</div>
</div>
@endsection
