@extends('admin.layouts.app')
@section('title', 'Media Library')
@section('page-title', 'Media Manager')

@push('styles')
<style>
    .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
    .media-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden; position: relative; transition: box-shadow 0.15s; }
    .media-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .media-card.selected { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(26,86,219,0.3); }
    .media-thumb { height: 120px; display: flex; align-items: center; justify-content: center; background: #f8fafc; overflow: hidden; }
    .media-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .media-thumb i { font-size: 2.5rem; color: #94a3b8; }
    .media-info { padding: 0.5rem 0.75rem; }
    .media-info .name { font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .media-info .meta { font-size: 0.7rem; color: #94a3b8; }
    .media-check { position: absolute; top: 8px; left: 8px; z-index: 2; }
    .upload-zone { border: 2px dashed #cbd5e1; border-radius: 0.75rem; padding: 2rem; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.15s; }
    .upload-zone:hover, .upload-zone.dragover { border-color: var(--primary); background: #eff6ff; }
    .folder-list .list-group-item { cursor: pointer; border: none; border-radius: 0.375rem; margin-bottom: 2px; }
    .folder-list .list-group-item.active { background: var(--primary); }
    .folder-list .list-group-item:hover:not(.active) { background: #f1f5f9; }
</style>
@endpush

@section('content')
<div class="row g-3">
    <div class="col-lg-3">
        <div class="card mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Folders</h6>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#folderModal"><i class="bi bi-folder-plus"></i></button>
            </div>
            <div class="list-group list-group-flush folder-list p-2">
                <a href="{{ route('admin.media.index') }}" class="list-group-item list-group-item-action {{ !request('folder') ? 'active' : '' }}">
                    <i class="bi bi-folder2-open"></i> All Files
                </a>
                @foreach($folders as $folder)
                <a href="{{ route('admin.media.index', ['folder' => $folder]) }}" class="list-group-item list-group-item-action {{ request('folder') === $folder ? 'active' : '' }}">
                    <i class="bi bi-folder"></i> {{ $folder }}
                </a>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0">Upload</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                    @csrf
                    <input type="hidden" name="folder" value="{{ request('folder', 'uploads') }}">
                    <div class="upload-zone mb-3" id="upload-zone" onclick="document.getElementById('file-input').click()">
                        <i class="bi bi-cloud-upload" style="font-size:2rem;color:#94a3b8"></i>
                        <p class="mb-0 mt-2 small">Click or drag files here</p>
                        <p class="text-muted" style="font-size:0.7rem">Images, PDF, ZIP, Excel — max 20MB</p>
                    </div>
                    <input type="file" name="files[]" id="file-input" class="d-none" multiple accept="image/*,video/*,.pdf,.zip,.xlsx,.xls,.doc,.docx">
                    <div class="form-check mb-3">
                        <input type="checkbox" name="convert_webp" value="1" class="form-check-input" id="webp" checked>
                        <label class="form-check-label small" for="webp">Convert images to WebP</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100" id="upload-btn" disabled>Upload</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2" method="GET">
                @if(request('folder'))<input type="hidden" name="folder" value="{{ request('folder') }}">@endif
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search media..." value="{{ request('search') }}" style="width:200px">
                <select name="type" class="form-select form-select-sm" style="width:auto">
                    <option value="">All Types</option>
                    <option value="image" @selected(request('type') === 'image')>Images</option>
                    <option value="video" @selected(request('type') === 'video')>Videos</option>
                    <option value="pdf" @selected(request('type') === 'pdf')>PDFs</option>
                    <option value="document" @selected(request('type') === 'document')>Documents</option>
                </select>
                <button class="btn btn-sm btn-outline-primary">Filter</button>
            </form>
            <form action="{{ route('admin.media.bulk-destroy') }}" method="POST" id="bulk-delete-form" onsubmit="return confirm('Delete selected files?')">
                @csrf @method('DELETE')
                <div id="bulk-ids"></div>
                <button type="submit" class="btn btn-sm btn-outline-danger" id="bulk-delete-btn" disabled>
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
            </form>
        </div>

        @if($media->count())
        <div class="media-grid">
            @foreach($media as $item)
            <div class="media-card" data-id="{{ $item->id }}">
                <div class="media-check">
                    <input type="checkbox" class="form-check-input media-select" value="{{ $item->id }}">
                </div>
                <div class="media-thumb">
                    @if($item->isImage())
                        <img src="{{ $item->url }}" alt="{{ $item->alt_text ?? $item->name }}" loading="lazy">
                    @else
                        <i class="bi {{ $item->icon }}"></i>
                    @endif
                </div>
                <div class="media-info">
                    <div class="name" title="{{ $item->name }}">{{ $item->name }}</div>
                    <div class="meta">{{ $item->human_size }} · {{ strtoupper(pathinfo($item->file_name, PATHINFO_EXTENSION)) }}</div>
                    <div class="mt-1 d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-1" data-bs-toggle="modal" data-bs-target="#editMedia{{ $item->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" onclick="copyUrl('{{ $item->url }}')" title="Copy URL"><i class="bi bi-clipboard"></i></button>
                        <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $media->withQueryString()->links() }}</div>
        @else
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-images" style="font-size:3rem"></i>
                <p class="mt-2 mb-0">No media files found. Upload some files to get started.</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Edit Modals --}}
@foreach($media as $item)
<div class="modal fade" id="editMedia{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.media.update', $item) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit Media</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    @if($item->isImage())
                    <img src="{{ $item->url }}" class="img-fluid rounded mb-3" alt="">
                    @endif
                    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="{{ $item->name }}" required></div>
                    <div class="mb-3"><label class="form-label">Alt Text</label><input type="text" name="alt_text" class="form-control" value="{{ $item->alt_text }}"></div>
                    <div class="mb-3"><label class="form-label">Folder</label><input type="text" name="folder" class="form-control" value="{{ $item->folder }}"></div>
                    <div class="mb-3"><label class="form-label">URL</label><input type="text" class="form-control form-control-sm" value="{{ $item->url }}" readonly onclick="this.select()"></div>
                    <p class="small text-muted mb-0">{{ $item->mime_type }} · {{ $item->human_size }} · {{ $item->created_at->format('d M Y') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
            <hr class="my-0">
            <form action="{{ route('admin.media.replace', $item) }}" method="POST" enctype="multipart/form-data" class="p-3">
                @csrf
                <label class="form-label small fw-bold">Replace File</label>
                <div class="input-group input-group-sm">
                    <input type="file" name="file" class="form-control" required>
                    <button class="btn btn-outline-warning">Replace</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- New Folder Modal --}}
<div class="modal fade" id="folderModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="{{ route('admin.media.folders.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">New Folder</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="text" name="folder" class="form-control" placeholder="e.g. products/brochures" required pattern="[a-zA-Z0-9_\-\/]+">
                    <small class="text-muted">Letters, numbers, hyphens, underscores, slashes only.</small>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const fileInput = document.getElementById('file-input');
const uploadBtn = document.getElementById('upload-btn');
const uploadZone = document.getElementById('upload-zone');

fileInput.addEventListener('change', () => {
    uploadBtn.disabled = !fileInput.files.length;
    if (fileInput.files.length) {
        uploadZone.querySelector('p').textContent = fileInput.files.length + ' file(s) selected';
    }
});

['dragenter', 'dragover'].forEach(e => uploadZone.addEventListener(e, ev => { ev.preventDefault(); uploadZone.classList.add('dragover'); }));
['dragleave', 'drop'].forEach(e => uploadZone.addEventListener(e, ev => { ev.preventDefault(); uploadZone.classList.remove('dragover'); }));
uploadZone.addEventListener('drop', ev => {
    fileInput.files = ev.dataTransfer.files;
    uploadBtn.disabled = !fileInput.files.length;
    uploadZone.querySelector('p').textContent = fileInput.files.length + ' file(s) selected';
});

document.querySelectorAll('.media-select').forEach(cb => {
    cb.addEventListener('change', updateBulk);
});

function updateBulk() {
    const checked = [...document.querySelectorAll('.media-select:checked')];
    const container = document.getElementById('bulk-ids');
    container.innerHTML = checked.map(c => `<input type="hidden" name="ids[]" value="${c.value}">`).join('');
    document.getElementById('bulk-delete-btn').disabled = !checked.length;
    checked.forEach(c => c.closest('.media-card').classList.add('selected'));
    document.querySelectorAll('.media-select:not(:checked)').forEach(c => c.closest('.media-card').classList.remove('selected'));
}

function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => alert('URL copied!'));
}
</script>
@endpush
