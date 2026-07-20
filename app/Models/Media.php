<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'user_id', 'name', 'file_name', 'path', 'disk', 'mime_type',
        'size', 'folder', 'alt_text',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'video/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function getIconAttribute(): string
    {
        if ($this->isImage()) {
            return 'bi-file-image';
        }
        if ($this->isVideo()) {
            return 'bi-file-play';
        }
        if ($this->isPdf()) {
            return 'bi-file-pdf';
        }
        if (str_contains($this->mime_type ?? '', 'zip') || str_contains($this->mime_type ?? '', 'compressed')) {
            return 'bi-file-zip';
        }
        if (str_contains($this->mime_type ?? '', 'spreadsheet') || str_contains($this->mime_type ?? '', 'excel')) {
            return 'bi-file-excel';
        }

        return 'bi-file-earmark';
    }
}
