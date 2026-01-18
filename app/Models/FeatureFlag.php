<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FeatureFlag extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'is_active',
        'rules',
        'metadata',
        'activated_at',
        'deactivated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rules' => 'array',
        'metadata' => 'array',
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    /**
     * Boot model events
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when feature flag is updated
        static::saved(function ($flag) {
            Cache::forget("feature_flag:{$flag->key}");
        });

        static::deleted(function ($flag) {
            Cache::forget("feature_flag:{$flag->key}");
        });
    }

    /**
     * Check if feature is enabled
     */
    public static function isEnabled(string $key, ?int $userId = null): bool
    {
        $flag = Cache::remember("feature_flag:{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (!$flag || !$flag->is_active) {
            return false;
        }

        // Check rollout rules if user ID provided
        if ($userId && $flag->rules) {
            return self::checkRules($flag->rules, $userId);
        }

        return true;
    }

    /**
     * Check rollout rules
     */
    protected static function checkRules(array $rules, int $userId): bool
    {
        // Check user whitelist
        if (isset($rules['users']) && in_array($userId, $rules['users'])) {
            return true;
        }

        // Check role whitelist
        if (isset($rules['roles'])) {
            $user = \App\Models\User::find($userId);
            if ($user && $user->hasAnyRole($rules['roles'])) {
                return true;
            }
        }

        // Check percentage rollout
        if (isset($rules['percentage'])) {
            $hash = crc32($userId);
            $bucket = $hash % 100;
            return $bucket < $rules['percentage'];
        }

        return false;
    }

    /**
     * Enable feature
     */
    public function enable(): void
    {
        $this->update([
            'is_active' => true,
            'activated_at' => now(),
            'deactivated_at' => null,
        ]);
    }

    /**
     * Disable feature
     */
    public function disable(): void
    {
        $this->update([
            'is_active' => false,
            'deactivated_at' => now(),
        ]);
    }

    /**
     * Scope: Active features
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Inactive features
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
