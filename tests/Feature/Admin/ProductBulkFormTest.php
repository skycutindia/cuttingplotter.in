<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductBulkFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_product_checkbox_submits_with_bulk_actions(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        foreach (['A', 'B', 'C'] as $suffix) {
            Product::query()->create([
                'name' => "Plotter {$suffix}",
                'slug' => 'plotter-'.strtolower($suffix),
                'sku' => "SKU-{$suffix}",
                'price' => 1000,
                'is_active' => true,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.products.index'));

        $response->assertOk();

        $xpath = $this->xpath($response->getContent());

        $this->assertSame(
            0,
            $xpath->query('//*[@id="bulk-form"]//form')->length,
            'Per-row clone and delete forms must not be nested inside the bulk-action form.'
        );

        $checks = $xpath->query('//input[contains(@class, "product-check")]');
        $this->assertSame(3, $checks->length);

        foreach ($checks as $check) {
            $this->assertSame('ids[]', $check->getAttribute('name'));
            $this->assertSame(
                'bulk-form',
                $check->getAttribute('form'),
                'Each row checkbox must belong to the bulk form, including rows after the first.'
            );
        }

        $this->assertSame(3, $xpath->query('//form[contains(@action, "/clone")]')->length);
        $this->assertSame(
            3,
            $xpath->query('//form[.//input[@name="_method" and @value="DELETE"]]')->length
        );
    }

    private function xpath(string $html): DOMXPath
    {
        $dom = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return new DOMXPath($dom);
    }
}
