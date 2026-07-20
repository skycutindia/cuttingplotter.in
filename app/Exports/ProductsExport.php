<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(protected array $filters = []) {}

    public function query()
    {
        $query = Product::query()->with(['brand', 'category'])->orderBy('id');

        if (! empty($this->filters['brand_id'])) {
            $query->where('brand_id', $this->filters['brand_id']);
        }
        if (! empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }
        if (isset($this->filters['is_active']) && $this->filters['is_active'] !== '') {
            $query->where('is_active', (bool) $this->filters['is_active']);
        }
        if (! empty($this->filters['ids'])) {
            $query->whereIn('id', $this->filters['ids']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'SKU', 'Product Code', 'Name', 'Slug', 'Brand', 'Category',
            'Short Description', 'Price', 'Offer Price', 'Stock', 'HSN', 'GST Rate',
            'Weight', 'Dimensions', 'Meta Title', 'Meta Description',
            'Is Featured', 'Is Trending', 'Is New Arrival', 'Is Best Seller', 'Is Active',
        ];
    }

    public function map($product): array
    {
        return [
            $product->sku,
            $product->product_code,
            $product->name,
            $product->slug,
            $product->brand?->name,
            $product->category?->name,
            $product->short_description,
            $product->price,
            $product->offer_price,
            $product->stock,
            $product->hsn,
            $product->gst_rate,
            $product->weight,
            $product->dimensions,
            $product->meta_title,
            $product->meta_description,
            $product->is_featured ? 1 : 0,
            $product->is_trending ? 1 : 0,
            $product->is_new_arrival ? 1 : 0,
            $product->is_best_seller ? 1 : 0,
            $product->is_active ? 1 : 0,
        ];
    }
}
