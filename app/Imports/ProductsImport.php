<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                $this->importRow($row);
            }
        });
    }

    public function rules(): array
    {
        return [
            '*.sku' => 'required',
            '*.name' => 'required',
        ];
    }

    protected function importRow(mixed $row): void
    {
        $sku = trim((string) $this->value($row, 'sku'));
        $name = trim((string) $this->value($row, 'name'));

        if ($sku === '' || $name === '') {
            $this->skipped++;

            return;
        }

        $existing = Product::withTrashed()->where('sku', $sku)->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
            }

            $existing->update($this->attributesFromRow($row, $name, $existing));
            $this->updated++;

            return;
        }

        $data = $this->attributesFromRow($row, $name, null);
        $data['sku'] = $sku;
        $data['slug'] = $this->uniqueSlug(Product::class, (string) ($data['slug'] ?? Str::slug($name)), null, 'product');

        Product::create($data);
        $this->created++;
    }

    /**
     * Blank cells mean "leave unchanged" on update. A price-only sheet must not
     * wipe brand, stock, flags, or the public slug.
     *
     * @return array<string, mixed>
     */
    protected function attributesFromRow(mixed $row, string $name, ?Product $existing): array
    {
        $data = ['name' => $name];

        if ($this->present($this->value($row, 'slug'))) {
            $data['slug'] = $this->uniqueSlug(
                Product::class,
                Str::slug((string) $this->value($row, 'slug')),
                $existing?->id,
                'product'
            );
        } elseif ($existing === null) {
            $data['slug'] = Str::slug($name);
        }

        if ($this->present($this->value($row, 'brand'))) {
            $data['brand_id'] = $this->resolveBrand((string) $this->value($row, 'brand'));
        }

        if ($this->present($this->value($row, 'category'))) {
            $data['category_id'] = $this->resolveCategory((string) $this->value($row, 'category'));
        }

        foreach (['product_code', 'short_description', 'hsn', 'dimensions', 'meta_title', 'meta_description'] as $column) {
            if ($this->present($this->value($row, $column))) {
                $data[$column] = $this->value($row, $column);
            }
        }

        foreach (['price', 'offer_price', 'gst_rate', 'weight'] as $column) {
            if ($this->present($this->value($row, $column))) {
                $data[$column] = $this->num($this->value($row, $column));
            }
        }

        if ($this->present($this->value($row, 'stock'))) {
            $data['stock'] = max(0, (int) $this->value($row, 'stock'));
        }

        foreach (['is_featured', 'is_trending', 'is_new_arrival', 'is_best_seller', 'is_active'] as $flag) {
            if ($this->present($this->value($row, $flag))) {
                $data[$flag] = $this->bool($this->value($row, $flag));
            }
        }

        return $data;
    }

    protected function resolveBrand(string $name): int
    {
        return $this->resolveNamedModel(Brand::class, $name, 'brand');
    }

    protected function resolveCategory(string $name): int
    {
        return $this->resolveNamedModel(Category::class, $name, 'category');
    }

    /**
     * @param  class-string<Brand|Category>  $modelClass
     */
    protected function resolveNamedModel(string $modelClass, string $name, string $fallbackSlug): int
    {
        $name = trim($name);
        $slug = Str::slug($name);

        $existing = $modelClass::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->first();

        if (! $existing && $slug !== '') {
            $existing = $modelClass::query()->where('slug', $slug)->first();
        }

        if ($existing) {
            return $existing->id;
        }

        return $modelClass::create([
            'name' => $name,
            'slug' => $this->uniqueSlug($modelClass, $slug, null, $fallbackSlug),
            'is_active' => true,
        ])->id;
    }

    /**
     * @param  class-string<Product|Brand|Category>  $modelClass
     */
    protected function uniqueSlug(string $modelClass, string $base, ?int $ignoreId, string $fallback): string
    {
        $base = $base !== '' ? $base : $fallback;
        $slug = $base;
        $i = 1;

        while (
            $modelClass::withTrashed()
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    protected function value(mixed $row, string $key): mixed
    {
        if (is_array($row) || $row instanceof \ArrayAccess) {
            return $row[$key] ?? null;
        }

        return null;
    }

    protected function present(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        return true;
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
