<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_form_keeps_inactive_brand_and_child_category_selected(): void
    {
        $admin = User::factory()->create();
        Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin->assignRole('super-admin');

        $brand = Brand::create([
            'name' => 'Roland',
            'slug' => 'roland',
            'is_active' => false,
        ]);
        $parent = Category::create([
            'name' => 'Cutting Plotters',
            'slug' => 'cutting-plotters',
        ]);
        $child = Category::create([
            'name' => 'Vinyl Cutters',
            'slug' => 'vinyl-cutters',
            'parent_id' => $parent->id,
        ]);
        $product = Product::create([
            'name' => 'Roland GR2-640',
            'slug' => 'roland-gr2-640',
            'sku' => 'ROL-GR2-640',
            'brand_id' => $brand->id,
            'category_id' => $child->id,
            'price' => 320000,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.products.edit', $product->id));

        $response->assertOk();
        $response->assertSee('value="'.$brand->id.'" selected', false);
        $response->assertSee('value="'.$child->id.'" selected', false);
        $response->assertSee('Cutting Plotters / Vinyl Cutters', false);
    }

    public function test_saving_the_edit_form_does_not_clear_brand_or_category(): void
    {
        $admin = User::factory()->create();
        Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin->assignRole('super-admin');

        $brand = Brand::create([
            'name' => 'Roland',
            'slug' => 'roland',
            'is_active' => false,
        ]);
        $parent = Category::create([
            'name' => 'Cutting Plotters',
            'slug' => 'cutting-plotters',
        ]);
        $child = Category::create([
            'name' => 'Vinyl Cutters',
            'slug' => 'vinyl-cutters',
            'parent_id' => $parent->id,
        ]);
        $product = Product::create([
            'name' => 'Roland GR2-640',
            'slug' => 'roland-gr2-640',
            'sku' => 'ROL-GR2-640',
            'brand_id' => $brand->id,
            'category_id' => $child->id,
            'price' => 320000,
            'stock' => 8,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'name' => 'Roland GR2-640',
            'sku' => 'ROL-GR2-640',
            'brand_id' => $brand->id,
            'category_id' => $child->id,
            'price' => 310000,
            'stock' => 8,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $product->refresh();
        $this->assertSame($brand->id, $product->brand_id);
        $this->assertSame($child->id, $product->category_id);
        $this->assertSame('310000.00', $product->price);
    }
}
