<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BrokerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'broker_id',
        'type',
        'file_key',
        'original_filename',
        'valid_until',
        'status',
        'notes',
    ];

    protected $casts = [
        'valid_until' => 'date',
    ];

    /**
     * Get the broker
     */
    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_key);
    }

    /**
     * Check if document is expired
     */
    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    /**
     * Check if document is valid
     */
    public function isValid(): bool
    {
        return $this->status === 'approved' && !$this->isExpired();
    }

    /**
     * Scope valid documents
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'approved')
            ->where(function($q) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>', now());
            });
    }

    /**
     * Scope expired documents
     */
    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<=', now());
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
