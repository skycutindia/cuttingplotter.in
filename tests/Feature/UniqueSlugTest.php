<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use App\Services\PageService;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UniqueSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_product_names_do_not_crash_and_keep_distinct_slugs(): void
    {
        $service = app(ProductService::class);

        $first = $service->create(['name' => 'Vinyl Cutter', 'sku' => 'VC-1']);
        $second = $service->create(['name' => 'Vinyl Cutter', 'sku' => 'VC-2']);

        $this->assertSame('vinyl-cutter', $first->slug);
        $this->assertSame('vinyl-cutter-1', $second->slug);
        $this->assertSame(2, Product::count());
    }

    public function test_recreating_a_soft_deleted_brand_does_not_crash(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.brands.store'), [
            'name' => 'Skycut',
        ])->assertRedirect(route('admin.brands.index'));

        $brand = Brand::where('slug', 'skycut')->firstOrFail();
        $brand->delete();

        $this->actingAs($admin)->post(route('admin.brands.store'), [
            'name' => 'Skycut',
        ])->assertRedirect(route('admin.brands.index'));

        $this->assertNotNull(Brand::where('slug', 'skycut-1')->first());
    }

    public function test_clearing_a_page_slug_regenerates_it_instead_of_writing_null(): void
    {
        $admin = $this->admin();
        $page = app(PageService::class)->create([
            'title' => 'About Us',
            'is_active' => true,
        ]);

        $this->assertSame('about-us', $page->slug);

        $this->actingAs($admin)->put(route('admin.pages.update', $page), [
            'title' => 'About Us',
            'slug' => '',
        ])->assertRedirect(route('admin.pages.edit', $page));

        $this->assertSame('about-us', $page->fresh()->slug);
        $this->assertSame(1, Page::withTrashed()->count());
    }

    protected function admin(): User
    {
        Role::findOrCreate('admin');
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }
}
