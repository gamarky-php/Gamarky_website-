<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    const UPDATED_AT = null; // Only created_at is needed

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'before_hash',
        'after_hash',
        'changes',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'changes' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the auditable model
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Log an action
     */
    public static function log(
        string $action,
        ?Model $model = null,
        ?array $changes = null,
        ?array $metadata = null
    ): self {
        $beforeHash = null;
        $afterHash = null;

        if ($model && $changes) {
            if (isset($changes['before'])) {
                $beforeHash = hash('sha256', json_encode($changes['before']));
            }
            if (isset($changes['after'])) {
                $afterHash = hash('sha256', json_encode($changes['after']));
            }
        }

        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'before_hash' => $beforeHash,
            'after_hash' => $afterHash,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Scope: Filter by action
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by model
     */
    public function scopeForModel($query, string $modelType, ?int $modelId = null)
    {
        $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }

    /**
     * Scope: Recent logs
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted action name
     */
    public function getFormattedActionAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->action));
    }

    /**
     * Check if changes exist
     */
    public function hasChanges(): bool
    {
        return !empty($this->changes);
    }

    /**
     * Get changed fields
     */
    public function getChangedFields(): array
    {
        if (!$this->hasChanges()) {
            return [];
        }

        return array_keys($this->changes['after'] ?? []);
    }
}
