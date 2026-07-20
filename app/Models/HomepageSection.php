<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = ['section_key', 'title', 'content', 'settings', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'settings' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
