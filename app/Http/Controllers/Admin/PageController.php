<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(protected PageService $pageService) {}

    public function index(): View
    {
        $pages = Page::withCount('sections')->orderBy('sort_order')->latest()->paginate(15);

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        $sectionTypes = config('page_sections.types');

        return view('admin.pages.create', compact('sectionTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'template' => 'nullable|string|max:50',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_homepage' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_homepage'] = $request->boolean('is_homepage');

        $page = $this->pageService->create($validated);

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page created. Add sections below.');
    }

    public function edit(Page $page): View
    {
        $page->load(['sections' => fn ($q) => $q->orderBy('sort_order')]);
        $sectionTypes = config('page_sections.types');

        return view('admin.pages.edit', compact('page', 'sectionTypes'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,'.$page->id,
            'template' => 'nullable|string|max:50',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_homepage' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_homepage'] = $request->boolean('is_homepage');

        $this->pageService->update($page, $validated);

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($page->is_homepage) {
            return back()->withErrors(['page' => 'Cannot delete the homepage. Assign another page first.']);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }

    public function storeSection(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:'.implode(',', array_keys(config('page_sections.types'))),
        ]);

        $this->pageService->addSection($page, $validated['type']);

        return back()->with('success', 'Section added.');
    }

    public function updateSection(Request $request, Page $page, PageSection $section): RedirectResponse
    {
        abort_unless($section->page_id === $page->id, 404);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|array',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $this->pageService->updateSection($section, $validated);

        return back()->with('success', 'Section updated.');
    }

    public function destroySection(Page $page, PageSection $section): RedirectResponse
    {
        abort_unless($section->page_id === $page->id, 404);
        $section->delete();

        return back()->with('success', 'Section removed.');
    }

    public function duplicateSection(Page $page, PageSection $section): RedirectResponse
    {
        abort_unless($section->page_id === $page->id, 404);
        $this->pageService->duplicateSection($section);

        return back()->with('success', 'Section duplicated.');
    }

    public function reorderSections(Request $request, Page $page): JsonResponse
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:page_sections,id',
        ]);

        $this->pageService->reorderSections($page, $validated['order']);

        return response()->json(['success' => true]);
    }

    public function toggleSection(Page $page, PageSection $section): JsonResponse
    {
        abort_unless($section->page_id === $page->id, 404);
        $section->update(['is_active' => ! $section->is_active]);

        return response()->json(['is_active' => $section->is_active]);
    }
}
