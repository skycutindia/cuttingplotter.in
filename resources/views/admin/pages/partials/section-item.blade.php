<div class="section-item d-flex align-items-center {{ $section->is_active ? '' : 'inactive' }}" data-id="{{ $section->id }}">
    <div class="section-handle"><i class="bi bi-grip-vertical"></i></div>
    <div class="flex-grow-1 py-2">
        <span class="badge bg-light text-dark section-type-badge me-2">
            <i class="bi {{ $sectionTypes[$section->type]['icon'] ?? 'bi-square' }}"></i>
            {{ $sectionTypes[$section->type]['label'] ?? $section->type }}
        </span>
        <strong>{{ $section->title }}</strong>
        @unless($section->is_active)<span class="badge bg-secondary ms-1">Hidden</span>@endunless
    </div>
    <div class="pe-2 d-flex gap-1">
        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#sectionModal{{ $section->id }}" title="Edit">
            <i class="bi bi-pencil"></i>
        </button>
        <form action="{{ route('admin.pages.sections.duplicate', [$section->page, $section]) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Duplicate"><i class="bi bi-copy"></i></button>
        </form>
        <form action="{{ route('admin.pages.sections.destroy', [$section->page, $section]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this section?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
        </form>
    </div>
</div>
