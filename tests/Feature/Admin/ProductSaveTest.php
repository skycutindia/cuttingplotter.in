<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductSaveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_blank_stock_is_stored_as_zero_and_product_code_is_kept(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Vinyl Cutter',
            'sku' => 'VC-1',
            'product_code' => 'PC-100',
            'stock' => '',
            'price' => '1000',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'VC-1')->first();

        $this->assertNotNull($product);
        $this->assertEquals(0, $product->stock);
        $this->assertSame('PC-100', $product->product_code);
    }

    public function test_update_persists_product_code_and_blank_stock_without_clearing_other_fields(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $product = Product::create([
            'name' => 'Vinyl Cutter',
            'slug' => 'vinyl-cutter',
            'sku' => 'VC-1',
            'product_code' => 'PC-OLD',
            'price' => 5000,
            'stock' => 12,
            'hsn' => '8443',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'name' => 'Vinyl Cutter 24',
            'sku' => 'VC-1',
            'product_code' => 'PC-NEW',
            'stock' => '',
            'price' => '4500',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product->refresh();

        $this->assertSame('Vinyl Cutter 24', $product->name);
        $this->assertSame('PC-NEW', $product->product_code);
        $this->assertEquals(0, $product->stock);
        $this->assertEquals(4500, (float) $product->price);
        $this->assertSame('8443', $product->hsn);
    }

    public function test_numeric_stock_is_stored_as_entered(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Printhead',
            'sku' => 'PH-1',
            'stock' => '7',
            'is_active' => '1',
        ])->assertRedirect(route('admin.products.index'));

        $this->assertEquals(7, Product::where('sku', 'PH-1')->first()->stock);
    }
}
