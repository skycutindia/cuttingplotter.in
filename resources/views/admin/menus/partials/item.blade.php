<div class="menu-item d-flex align-items-center justify-content-between" data-id="{{ $item->id }}">
    <div>
        @if($item->icon)<i class="bi {{ $item->icon }} me-1"></i>@endif
        <strong>{{ $item->title }}</strong>
        @if($item->url)<small class="text-muted ms-2">{{ $item->url }}</small>@endif
        @unless($item->is_active)<span class="badge bg-secondary ms-1">Hidden</span>@endunless
    </div>
    <div class="d-flex gap-1">
        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editItem{{ $item->id }}"><i class="bi bi-pencil"></i></button>
        <form action="{{ route('admin.menus.items.destroy', [$menu, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
    </div>
</div>
@php $children = $allItems->where('parent_id', $item->id); @endphp
@if($children->count())
<div class="menu-children">
    @foreach($children as $child)
        @include('admin.menus.partials.item', ['item' => $child, 'menu' => $menu, 'allItems' => $allItems])
    @endforeach
</div>
@endif
