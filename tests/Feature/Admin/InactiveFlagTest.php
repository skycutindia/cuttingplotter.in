<?php

namespace Tests\Feature\Admin;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InactiveFlagTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_unchecked_active_box_keeps_new_records_and_edits_inactive(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Draft Cutter',
                'sku' => 'DRAFT-CUTTER',
            ])
            ->assertRedirect();

        $this->assertFalse(Product::where('sku', 'DRAFT-CUTTER')->firstOrFail()->is_active);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Live Cutter',
                'sku' => 'LIVE-CUTTER',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->assertTrue(Product::where('sku', 'LIVE-CUTTER')->firstOrFail()->is_active);

        $this->actingAs($admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Draft Page',
            ])
            ->assertRedirect();

        $page = Page::where('slug', 'draft-page')->firstOrFail();
        $this->assertFalse($page->is_active);

        $section = $page->sections()->create([
            'type' => 'heading',
            'title' => 'Intro',
            'content' => ['text' => 'Hello'],
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.pages.sections.update', [$page, $section]), [
                'title' => 'Intro',
                'content' => ['text' => 'Hello'],
            ])
            ->assertRedirect();

        $this->assertFalse($section->fresh()->is_active);

        $menu = Menu::create(['name' => 'Header', 'location' => 'header', 'is_active' => true]);
        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Products',
            'url' => '/products',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.menus.items.update', [$menu, $item]), [
                'title' => 'Products',
                'url' => '/products',
            ])
            ->assertRedirect();

        $this->assertFalse($item->fresh()->is_active);
    }
}
