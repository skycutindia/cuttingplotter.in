<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuService
{
    public function getByLocation(string $location)
    {
        return Cache::remember("menu.{$location}", 3600, function () use ($location) {
            return Menu::where('location', $location)
                ->where('is_active', true)
                ->with(['items' => fn ($q) => $q->where('is_active', true)->with(['children' => fn ($c) => $c->where('is_active', true)])])
                ->first();
        });
    }

    public function clearCache(?string $location = null): void
    {
        if ($location) {
            Cache::forget("menu.{$location}");
        } else {
            foreach (['header', 'footer', 'mobile'] as $loc) {
                Cache::forget("menu.{$loc}");
            }
        }
    }

    public function addItem(Menu $menu, array $data): MenuItem
    {
        $maxOrder = $menu->allItems()->where('parent_id', $data['parent_id'] ?? null)->max('sort_order') ?? -1;
        $data['sort_order'] = $maxOrder + 1;

        $item = $menu->allItems()->create($data);
        $this->clearCache($menu->location);

        return $item;
    }

    public function reorderItems(Menu $menu, array $items): void
    {
        DB::transaction(function () use ($items) {
            $this->saveOrder($items);
        });
        $this->clearCache($menu->location);
    }

    protected function saveOrder(array $items, ?int $parentId = null): void
    {
        foreach ($items as $order => $item) {
            MenuItem::where('id', $item['id'])->update([
                'sort_order' => $order,
                'parent_id' => $parentId,
            ]);
            if (! empty($item['children'])) {
                $this->saveOrder($item['children'], $item['id']);
            }
        }
    }
}
