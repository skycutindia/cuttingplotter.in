<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assigned_to', 'name', 'email', 'phone', 'company', 'source', 'type',
        'message', 'form_data', 'status', 'notes', 'reminder_at', 'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'form_data' => 'array',
            'reminder_at' => 'datetime',
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
