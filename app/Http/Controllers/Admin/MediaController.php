<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(protected MediaService $mediaService) {}

    public function index(Request $request): View
    {
        $media = $this->mediaService->paginate($request->only('folder', 'search', 'type'));
        $folders = $this->mediaService->folders();

        return view('admin.media.index', compact('media', 'folders'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:20480',
            'folder' => 'nullable|string|max:100',
            'convert_webp' => 'boolean',
        ]);

        $uploaded = $this->mediaService->uploadMany(
            $request->file('files', []),
            $request->input('folder', 'uploads'),
            $request->boolean('convert_webp', true)
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => count($uploaded),
                'media' => collect($uploaded)->map(fn (Media $m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                    'url' => $m->url,
                    'path' => $m->path,
                ]),
            ]);
        }

        return back()->with('success', count($uploaded).' file(s) uploaded successfully.');
    }

    public function update(Request $request, Media $medium): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'folder' => 'nullable|string|max:100',
        ]);

        $medium->update($validated);

        return back()->with('success', 'Media updated.');
    }

    public function replace(Request $request, Media $medium): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'convert_webp' => 'boolean',
        ]);

        $this->mediaService->replace(
            $medium,
            $request->file('file'),
            $request->boolean('convert_webp', true)
        );

        return back()->with('success', 'File replaced.');
    }

    public function destroy(Media $medium): RedirectResponse
    {
        $this->mediaService->delete($medium);

        return back()->with('success', 'File deleted.');
    }

    public function destroyBulk(Request $request): RedirectResponse
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:media,id']);

        $count = 0;
        foreach (Media::whereIn('id', $request->ids)->get() as $item) {
            $this->mediaService->delete($item);
            $count++;
        }

        return back()->with('success', "{$count} file(s) deleted.");
    }

    public function storeFolder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'folder' => 'required|string|max:100|regex:/^[a-zA-Z0-9_\-\/]+$/',
        ]);

        $this->mediaService->createFolder($validated['folder']);

        return redirect()->route('admin.media.index', ['folder' => $validated['folder']])
            ->with('success', 'Folder created.');
    }

    public function picker(Request $request): View|JsonResponse
    {
        $media = $this->mediaService->paginate(
            array_merge($request->only('folder', 'search', 'type'), ['type' => $request->input('type', 'image')]),
            18
        );

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $media->map(fn (Media $m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                    'url' => $m->url,
                    'path' => $m->path,
                    'alt_text' => $m->alt_text,
                ]),
                'next_page' => $media->hasMorePages() ? $media->currentPage() + 1 : null,
            ]);
        }

        return view('admin.media.picker', [
            'media' => $media,
            'folders' => $this->mediaService->folders(),
        ]);
    }
}
