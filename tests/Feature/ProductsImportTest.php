<?php

namespace Tests\Feature;

use App\Imports\ProductsImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductsImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_partial_update_does_not_wipe_omitted_fields(): void
    {
        $brand = Brand::create([
            'name' => 'Skycut',
            'slug' => 'skycut-india',
            'is_active' => true,
        ]);
        $category = Category::create([
            'name' => 'Plotters',
            'slug' => 'plotters',
            'is_active' => true,
        ]);
        $product = Product::create([
            'name' => 'Vinyl Cutter',
            'slug' => 'custom-vinyl-url',
            'sku' => 'SKU-1',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'short_description' => 'Keep me',
            'price' => 1000,
            'offer_price' => 900,
            'stock' => 12,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $import = new ProductsImport;
        $import->collection(new Collection([
            new Collection([
                'sku' => 'SKU-1',
                'name' => 'Vinyl Cutter',
                'price' => '1200',
            ]),
        ]));

        $product->refresh();

        $this->assertSame(1, $import->updated);
        $this->assertSame(0, $import->created);
        $this->assertSame('1200.00', $product->price);
        $this->assertSame('900.00', $product->offer_price);
        $this->assertSame($brand->id, $product->brand_id);
        $this->assertSame($category->id, $product->category_id);
        $this->assertSame(12, $product->stock);
        $this->assertSame('Keep me', $product->short_description);
        $this->assertSame('custom-vinyl-url', $product->slug);
        $this->assertTrue($product->is_featured);
        $this->assertTrue($product->is_active);
        $this->assertSame(1, Brand::count());
    }

    public function test_explicit_cells_update_product_and_reuse_brand_by_name(): void
    {
        $brand = Brand::create([
            'name' => 'Roland',
            'slug' => 'roland-printers',
            'is_active' => true,
        ]);
        $product = Product::create([
            'name' => 'Old Name',
            'slug' => 'old-name',
            'sku' => 'SKU-2',
            'brand_id' => null,
            'price' => 100,
            'stock' => 4,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $import = new ProductsImport;
        $import->collection(new Collection([
            new Collection([
                'sku' => 'SKU-2',
                'name' => 'Updated Cutter',
                'slug' => 'updated-cutter',
                'brand' => 'Roland',
                'stock' => '0',
                'is_featured' => '0',
                'is_active' => '0',
            ]),
        ]));

        $product->refresh();

        $this->assertSame(1, $import->updated);
        $this->assertSame('Updated Cutter', $product->name);
        $this->assertSame('updated-cutter', $product->slug);
        $this->assertSame($brand->id, $product->brand_id);
        $this->assertSame(0, $product->stock);
        $this->assertFalse($product->is_featured);
        $this->assertFalse($product->is_active);
        $this->assertSame(1, Brand::count());
    }

    public function test_new_product_slug_skips_soft_deleted_collision(): void
    {
        $deleted = Product::create([
            'name' => 'Vinyl Cutter',
            'slug' => 'vinyl-cutter',
            'sku' => 'OLD-SKU',
        ]);
        $deleted->delete();

        $import = new ProductsImport;
        $import->collection(new Collection([
            new Collection([
                'sku' => 'NEW-SKU',
                'name' => 'Vinyl Cutter',
            ]),
        ]));

        $created = Product::where('sku', 'NEW-SKU')->first();

        $this->assertNotNull($created);
        $this->assertSame(1, $import->created);
        $this->assertSame('vinyl-cutter-1', $created->slug);
        $this->assertTrue($created->is_active);
        $this->assertSame(0, $created->stock);
    }

    public function test_rows_missing_sku_or_name_are_skipped(): void
    {
        $import = new ProductsImport;
        $import->collection(new Collection([
            new Collection(['sku' => '', 'name' => 'Nameless']),
            new Collection(['sku' => 'SKU-3', 'name' => '   ']),
        ]));

        $this->assertSame(2, $import->skipped);
        $this->assertSame(0, Product::count());
    }
}
