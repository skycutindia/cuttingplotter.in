@extends('admin.layouts.app')
@section('title', 'Edit Page')
@section('page-title', 'Page Builder: '.$page->title)

@push('styles')
<style>
    .section-builder { min-height: 200px; }
    .section-item { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; margin-bottom: 0.75rem; cursor: grab; }
    .section-item.sortable-ghost { opacity: 0.4; background: #e0e7ff; }
    .section-item.inactive { opacity: 0.6; }
    .section-handle { cursor: grab; color: #94a3b8; padding: 0.75rem; }
    .section-type-badge { font-size: 0.75rem; }
    .add-section-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.5rem; }
    .add-section-btn { padding: 0.75rem; text-align: center; border: 1px dashed #cbd5e1; border-radius: 0.5rem; background: #f8fafc; cursor: pointer; font-size: 0.8rem; transition: all 0.15s; }
    .add-section-btn:hover { border-color: var(--primary); background: #eff6ff; color: var(--primary); }
    .add-section-btn i { display: block; font-size: 1.25rem; margin-bottom: 0.25rem; }
</style>
@endpush

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <form action="{{ route('admin.pages.update', $page) }}" method="POST">
            @csrf @method('PUT')
            <div class="card mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Page Settings</h6>
                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title', $page->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control form-control-sm" value="{{ old('slug', $page->slug) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control form-control-sm" value="{{ old('meta_title', $page->meta_title) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control form-control-sm" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" @checked($page->is_active)>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_homepage" value="1" class="form-check-input" id="homepage" @checked($page->is_homepage)>
                        <label class="form-check-label" for="homepage">Homepage</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Save Settings</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0">Add Section</h6></div>
            <div class="card-body">
                <div class="add-section-grid">
                    @foreach($sectionTypes as $type => $config)
                    <form action="{{ route('admin.pages.sections.store', $page) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit" class="add-section-btn w-100 border-0">
                            <i class="bi {{ $config['icon'] }}"></i>
                            {{ $config['label'] }}
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Page Sections <span class="badge bg-secondary">{{ $page->sections->count() }}</span></h6>
                <small class="text-muted"><i class="bi bi-grip-vertical"></i> Drag to reorder</small>
            </div>
            <div class="card-body section-builder" id="section-list">
                @forelse($page->sections as $section)
                    @include('admin.pages.partials.section-item', ['section' => $section, 'sectionTypes' => $sectionTypes])
                @empty
                    <div class="text-center text-muted py-5" id="empty-state">
                        <i class="bi bi-layout-text-window-reverse" style="font-size:2.5rem"></i>
                        <p class="mt-2">No sections yet. Add sections from the left panel.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@foreach($page->sections as $section)
<div class="modal fade" id="sectionModal{{ $section->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.pages.sections.update', [$page, $section]) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit: {{ $sectionTypes[$section->type]['label'] ?? $section->type }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Section Title (admin label)</label>
                        <input type="text" name="title" class="form-control" value="{{ $section->title }}">
                    </div>
                    @include('admin.pages.partials.section-form', ['section' => $section, 'type' => $section->type, 'config' => $sectionTypes[$section->type] ?? []])
                    <div class="form-check mt-3">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="active{{ $section->id }}" @checked($section->is_active)>
                        <label class="form-check-label" for="active{{ $section->id }}">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Section</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const list = document.getElementById('section-list');
    if (!list) return;

    new Sortable(list, {
        handle: '.section-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: function() {
            const order = [...list.querySelectorAll('.section-item')].map(el => el.dataset.id);
            fetch('{{ route('admin.pages.sections.reorder', $page) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ order }),
            });
        }
    });
});
</script>
@endpush
