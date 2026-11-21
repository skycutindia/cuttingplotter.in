<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slugs
{
    /**
     * Return a slug that does not collide with a live or soft-deleted row.
     *
     * Soft-deleted rows still occupy the unique index, so a plain exists()
     * check lets create() throw a query exception.
     */
    public static function unique(string $modelClass, string $base, ?int $ignoreId = null): string
    {
        $base = $base !== '' ? $base : 'item';
        $slug = $base;
        $suffix = 1;

        while (static::taken($modelClass, $slug, $ignoreId)) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    protected static function taken(string $modelClass, string $slug, ?int $ignoreId): bool
    {
        /** @var class-string<Model> $modelClass */
        $query = in_array(SoftDeletes::class, class_uses_recursive($modelClass), true)
            ? $modelClass::withTrashed()
            : $modelClass::query();

        return $query
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists();
    }
}
