<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'blog_category_id', 'author_id', 'title', 'slug', 'excerpt', 'content',
        'featured_image', 'gallery', 'tags', 'internal_links', 'external_links',
        'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
        'og_data', 'faq', 'status', 'published_at', 'views',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'tags' => 'array',
            'internal_links' => 'array',
            'external_links' => 'array',
            'og_data' => 'array',
            'faq' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
