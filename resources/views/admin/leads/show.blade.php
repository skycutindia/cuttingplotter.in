@extends('admin.layouts.app')
@section('title', 'Lead Details')
@section('page-title', 'Lead: '.$lead->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white"><h6 class="mb-0">Lead Information</h6></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $lead->name }}</dd>
                    <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $lead->email }}</dd>
                    <dt class="col-sm-3">Phone</dt><dd class="col-sm-9">{{ $lead->phone }}</dd>
                    <dt class="col-sm-3">Company</dt><dd class="col-sm-9">{{ $lead->company ?? '-' }}</dd>
                    <dt class="col-sm-3">Type</dt><dd class="col-sm-9"><span class="badge bg-secondary">{{ $lead->type }}</span></dd>
                    <dt class="col-sm-3">Source</dt><dd class="col-sm-9">{{ $lead->source }}</dd>
                    <dt class="col-sm-3">Message</dt><dd class="col-sm-9">{{ $lead->message ?? '-' }}</dd>
                    <dt class="col-sm-3">Submitted</dt><dd class="col-sm-9">{{ $lead->created_at->format('d M Y, h:i A') }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <form action="{{ route('admin.leads.update', $lead) }}" method="POST">
            @csrf @method('PUT')
            <div class="card">
                <div class="card-header bg-white"><h6 class="mb-0">Update Lead</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['new','contacted','qualified','proposal','won','lost'] as $s)
                            <option value="{{ $s }}" @selected($lead->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Assign To</label>
                        <select name="assigned_to" class="form-select"><option value="">Unassigned</option>
                            @foreach($users as $user)<option value="{{ $user->id }}" @selected($lead->assigned_to == $user->id)>{{ $user->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes', $lead->notes) }}</textarea></div>
                    <div class="mb-3"><label class="form-label">Reminder</label><input type="datetime-local" name="reminder_at" class="form-control" value="{{ $lead->reminder_at?->format('Y-m-d\TH:i') }}"></div>
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
