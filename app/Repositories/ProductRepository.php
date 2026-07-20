<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Product);
    }

    public function paginateWithRelations(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['brand', 'category', 'images'])
            ->latest();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->paginate($perPage);
    }

    public function findBySlugWithRelations(string $slug): ?Product
    {
        return $this->model->newQuery()
            ->with(['brand', 'category', 'subcategory', 'images', 'specifications', 'downloads', 'relatedProducts', 'compatibleProducts', 'reviews'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function featured(int $limit = 8)
    {
        return $this->model->newQuery()
            ->with(['brand', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    public function frontendPaginate(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['brand', 'category', 'images'])
            ->where('is_active', true)
            ->where('is_approved', true);

        if (! empty($filters['brand'])) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $filters['brand']));
        }

        if (! empty($filters['category'])) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $filters['category']));
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $sort = $filters['sort'] ?? 'latest';
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(offer_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(offer_price, price) DESC'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        return $query->paginate($perPage);
    }
}
