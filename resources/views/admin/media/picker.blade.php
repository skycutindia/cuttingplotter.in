<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Select Media</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .picker-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 0.75rem; }
        .picker-item { border: 2px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden; cursor: pointer; height: 100px; display: flex; align-items: center; justify-content: center; background: #f8fafc; }
        .picker-item:hover, .picker-item.selected { border-color: #1a56db; }
        .picker-item img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
<body class="p-3">
    <div class="d-flex gap-2 mb-3">
        <form class="d-flex gap-2 flex-grow-1" method="GET">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
            <select name="folder" class="form-select form-select-sm" style="width:auto">
                <option value="">All Folders</option>
                @foreach($folders as $f)<option value="{{ $f }}" @selected(request('folder') === $f)>{{ $f }}</option>@endforeach
            </select>
            <button class="btn btn-sm btn-outline-primary">Search</button>
        </form>
    </div>
    <div class="picker-grid">
        @foreach($media as $item)
        <div class="picker-item" data-url="{{ $item->url }}" data-path="{{ $item->path }}" data-alt="{{ $item->alt_text }}" data-id="{{ $item->id }}" onclick="selectItem(this)">
            @if($item->isImage())
                <img src="{{ $item->url }}" alt="{{ $item->name }}">
            @else
                <i class="bi {{ $item->icon }}" style="font-size:2rem;color:#94a3b8"></i>
            @endif
        </div>
        @endforeach
    </div>
    <div class="mt-3">{{ $media->withQueryString()->links() }}</div>
    <div class="mt-3 d-flex justify-content-end gap-2">
        <button class="btn btn-secondary btn-sm" onclick="window.close()">Cancel</button>
        <button class="btn btn-primary btn-sm" id="select-btn" disabled onclick="confirmSelect()">Select</button>
    </div>
    <script>
        let selected = null;
        function selectItem(el) {
            document.querySelectorAll('.picker-item').forEach(i => i.classList.remove('selected'));
            el.classList.add('selected');
            selected = { url: el.dataset.url, path: el.dataset.path, alt: el.dataset.alt, id: el.dataset.id };
            document.getElementById('select-btn').disabled = false;
        }
        function confirmSelect() {
            if (selected && window.opener) {
                window.opener.postMessage({ type: 'media-selected', media: selected }, '*');
                window.close();
            }
        }
    </script>
</body>
</html>
