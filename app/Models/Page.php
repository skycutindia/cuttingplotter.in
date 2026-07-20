<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'template', 'content', 'featured_image',
        'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
        'og_data', 'schema_data', 'faq', 'is_homepage', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'og_data' => 'array',
            'schema_data' => 'array',
            'faq' => 'array',
            'is_homepage' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }
}
