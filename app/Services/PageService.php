<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageService
{
    public function create(array $data): Page
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (! empty($data['is_homepage'])) {
            Page::query()->update(['is_homepage' => false]);
        }

        return Page::create($data);
    }

    public function update(Page $page, array $data): Page
    {
        if (! empty($data['is_homepage'])) {
            Page::query()->where('id', '!=', $page->id)->update(['is_homepage' => false]);
        }

        $page->update($data);

        return $page->fresh();
    }

    public function addSection(Page $page, string $type, array $content = [], ?string $title = null): PageSection
    {
        $maxOrder = $page->sections()->max('sort_order') ?? -1;

        return $page->sections()->create([
            'type' => $type,
            'title' => $title ?? config("page_sections.types.{$type}.label", $type),
            'content' => $content,
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
        ]);
    }

    public function updateSection(PageSection $section, array $data): PageSection
    {
        $section->update($data);

        return $section->fresh();
    }

    public function reorderSections(Page $page, array $orderedIds): void
    {
        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $order => $id) {
                PageSection::where('id', $id)->update(['sort_order' => $order]);
            }
        });
    }

    public function duplicateSection(PageSection $section): PageSection
    {
        $maxOrder = $section->page->sections()->max('sort_order') ?? 0;

        return $section->page->sections()->create([
            'type' => $section->type,
            'title' => $section->title.' (Copy)',
            'content' => $section->content,
            'settings' => $section->settings,
            'sort_order' => $maxOrder + 1,
            'is_active' => $section->is_active,
        ]);
    }
}
