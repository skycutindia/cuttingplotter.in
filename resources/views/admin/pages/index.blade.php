@extends('admin.layouts.app')
@section('title', 'Pages')
@section('page-title', 'Page Builder')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">Create and manage dynamic pages with drag-and-drop sections.</p>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Page</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Sections</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td>
                        <strong>{{ $page->title }}</strong>
                        @if($page->is_homepage)<span class="badge bg-info ms-1">Homepage</span>@endif
                    </td>
                    <td><code>/page/{{ $page->slug }}</code></td>
                    <td>{{ $page->sections_count }} sections</td>
                    <td><span class="badge bg-{{ $page->is_active ? 'success' : 'secondary' }}">{{ $page->is_active ? 'Active' : 'Draft' }}</span></td>
                    <td>
                        <a href="{{ route('pages.show', $page->slug) }}" class="btn btn-sm btn-outline-secondary" target="_blank" title="Preview"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary" title="Edit & Build"><i class="bi bi-pencil"></i></a>
                        @unless($page->is_homepage)
                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this page?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endunless
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No pages yet. Create your first page.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pages->hasPages())<div class="card-footer">{{ $pages->links() }}</div>@endif
</div>
@endsection
