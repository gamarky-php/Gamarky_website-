<?php

namespace App\Models;

/**
 * Webhook Model
 * 
 * Purpose: إدارة Webhooks للتكامل مع الأنظمة الخارجية
 * Relations: User
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Webhook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'method',
        'subscribed_events',
        'secret_key',
        'headers',
        'is_active',
        'retry_count',
        'timeout_seconds',
        'total_calls',
        'successful_calls',
        'failed_calls',
        'last_called_at',
        'last_error',
    ];

    protected $casts = [
        'subscribed_events' => 'array',
        'headers' => 'array',
        'is_active' => 'boolean',
        'last_called_at' => 'datetime',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEvent($query, string $event)
    {
        return $query->whereJsonContains('subscribed_events', $event);
    }

    // Methods
    public function recordCall(bool $success, int $httpCode = null, string $error = null): void
    {
        $this->total_calls++;
        
        if ($success) {
            $this->successful_calls++;
        } else {
            $this->failed_calls++;
            $this->last_error = $error;
        }
        
        $this->last_called_at = now();
        $this->save();
    }

    public function getSuccessRateAttribute(): float
    {
        if ($this->total_calls == 0) {
            return 0;
        }
        
        return round(($this->successful_calls / $this->total_calls) * 100, 2);
    }
}
