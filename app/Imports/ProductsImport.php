<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public int $created = 0;

    public int $updated = 0;

    public int $skipped = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $sku = trim((string) ($row['sku'] ?? ''));
            $name = trim((string) ($row['name'] ?? ''));

            if ($sku === '' || $name === '') {
                $this->skipped++;

                continue;
            }

            $brandId = null;
            if (! empty($row['brand'])) {
                $brand = Brand::firstOrCreate(
                    ['slug' => Str::slug($row['brand'])],
                    ['name' => $row['brand'], 'is_active' => true]
                );
                $brandId = $brand->id;
            }

            $categoryId = null;
            if (! empty($row['category'])) {
                $category = Category::firstOrCreate(
                    ['slug' => Str::slug($row['category'])],
                    ['name' => $row['category'], 'is_active' => true]
                );
                $categoryId = $category->id;
            }

            $data = [
                'name' => $name,
                'slug' => ! empty($row['slug']) ? Str::slug($row['slug']) : Str::slug($name),
                'product_code' => $row['product_code'] ?? null,
                'brand_id' => $brandId,
                'category_id' => $categoryId,
                'short_description' => $row['short_description'] ?? null,
                'price' => $this->num($row['price'] ?? null),
                'offer_price' => $this->num($row['offer_price'] ?? null),
                'stock' => (int) ($row['stock'] ?? 0),
                'hsn' => $row['hsn'] ?? null,
                'gst_rate' => $this->num($row['gst_rate'] ?? null),
                'weight' => $this->num($row['weight'] ?? null),
                'dimensions' => $row['dimensions'] ?? null,
                'meta_title' => $row['meta_title'] ?? null,
                'meta_description' => $row['meta_description'] ?? null,
                'is_featured' => $this->bool($row['is_featured'] ?? 0),
                'is_trending' => $this->bool($row['is_trending'] ?? 0),
                'is_new_arrival' => $this->bool($row['is_new_arrival'] ?? 0),
                'is_best_seller' => $this->bool($row['is_best_seller'] ?? 0),
                'is_active' => $this->bool($row['is_active'] ?? 1),
            ];

            $existing = Product::withTrashed()->where('sku', $sku)->first();

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }
                $existing->update($data);
                $this->updated++;
            } else {
                $data['sku'] = $sku;
                // Ensure unique slug
                $baseSlug = $data['slug'];
                $i = 1;
                while (Product::where('slug', $data['slug'])->exists()) {
                    $data['slug'] = $baseSlug.'-'.$i++;
                }
                Product::create($data);
                $this->created++;
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.sku' => 'required',
            '*.name' => 'required',
        ];
    }

    protected function bool(mixed $value): bool
    {
        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'y'], true);
    }

    protected function num(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }
}
