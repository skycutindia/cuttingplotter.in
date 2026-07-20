<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Repositories\ProductRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(protected ProductRepository $repository) {}

    public function create(array $data, array $images = [], array $specifications = []): Product
    {
        return DB::transaction(function () use ($data, $images, $specifications) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $product = $this->repository->create($data);
            $this->syncImages($product, $images);
            $this->syncSpecifications($product, $specifications);

            return $product->load(['brand', 'category', 'images', 'specifications']);
        });
    }

    public function update(int $id, array $data, array $images = [], array $specifications = []): Product
    {
        return DB::transaction(function () use ($id, $data, $images, $specifications) {
            $product = $this->repository->update($id, $data);

            if (! empty($images)) {
                $this->syncImages($product, $images);
            }

            if (! empty($specifications)) {
                $product->specifications()->delete();
                $this->syncSpecifications($product, $specifications);
            }

            return $product->load(['brand', 'category', 'images', 'specifications']);
        });
    }

    public function clone(int $id): Product
    {
        $original = $this->repository->find($id);
        $data = $original->toArray();
        unset($data['id'], $data['created_at'], $data['updated_at'], $data['deleted_at']);
        $data['name'] .= ' (Copy)';
        $data['slug'] = Str::slug($data['name']).'-'.time();
        $data['sku'] = $data['sku'].'-copy-'.time();

        return $this->repository->create($data);
    }

    protected function syncImages(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $path = $image->store('products/'.$product->id, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }
    }

    protected function syncSpecifications(Product $product, array $specifications): void
    {
        foreach ($specifications as $index => $spec) {
            if (! empty($spec['label']) && ! empty($spec['value'])) {
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group' => $spec['group'] ?? null,
                    'label' => $spec['label'],
                    'value' => $spec['value'],
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
