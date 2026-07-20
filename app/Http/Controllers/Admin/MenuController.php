<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function __construct(protected MenuService $menuService) {}

    public function index(): View
    {
        $menus = Menu::withCount('allItems')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function edit(Menu $menu): View
    {
        $menu->load(['allItems' => fn ($q) => $q->orderBy('sort_order')]);
        $parentItems = $menu->allItems()->whereNull('parent_id')->get();

        return view('admin.menus.edit', compact('menu', 'parentItems'));
    }

    public function storeItem(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'nullable|in:_self,_blank',
            'icon' => 'nullable|string|max:50',
        ]);

        $validated['is_active'] = true;
        $validated['target'] = $validated['target'] ?? '_self';

        $this->menuService->addItem($menu, $validated);

        return back()->with('success', 'Menu item added.');
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $item): RedirectResponse
    {
        abort_unless($item->menu_id === $menu->id, 404);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'nullable|in:_self,_blank',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $item->update($validated);
        $this->menuService->clearCache($menu->location);

        return back()->with('success', 'Menu item updated.');
    }

    public function destroyItem(Menu $menu, MenuItem $item): RedirectResponse
    {
        abort_unless($item->menu_id === $menu->id, 404);
        $item->children()->delete();
        $item->delete();
        $this->menuService->clearCache($menu->location);

        return back()->with('success', 'Menu item removed.');
    }

    public function reorderItems(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate(['items' => 'required|array']);
        $this->menuService->reorderItems($menu, $validated['items']);

        return response()->json(['success' => true]);
    }
}
