<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id', 'category_id', 'subcategory_id', 'name', 'slug', 'sku', 'product_code',
        'short_description', 'description', 'features', 'applications', 'tags', 'video_url',
        'brochure', 'manual', 'price', 'offer_price', 'stock', 'hsn', 'gst_rate',
        'weight', 'dimensions', 'meta_title', 'meta_description', 'meta_keywords',
        'canonical_url', 'og_data', 'schema_data', 'faq', 'is_featured', 'is_trending',
        'is_new_arrival', 'is_best_seller', 'is_active', 'is_approved', 'views', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'applications' => 'array',
            'tags' => 'array',
            'og_data' => 'array',
            'schema_data' => 'array',
            'faq' => 'array',
            'price' => 'decimal:2',
            'offer_price' => 'decimal:2',
            'gst_rate' => 'decimal:2',
            'weight' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(ProductDownload::class)->orderBy('sort_order');
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_product_id');
    }

    public function compatibleProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_compatible', 'product_id', 'compatible_product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function primaryImage(): ?ProductImage
    {
        return $this->images()->where('is_primary', true)->first()
            ?? $this->images()->first();
    }

    public function getEffectivePriceAttribute(): ?float
    {
        return $this->offer_price ?? $this->price;
    }
}
