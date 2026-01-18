<?php

namespace App\Services;

use App\Models\FeatureFlag;
use Illuminate\Support\Facades\Cache;

class FeatureFlagService
{
    /**
     * Check if a feature is enabled for current user
     */
    public function isEnabled(string $featureKey): bool
    {
        $userId = auth()->id();
        return FeatureFlag::isEnabled($featureKey, $userId);
    }

    /**
     * Check if feature is enabled for specific user
     */
    public function isEnabledFor(string $featureKey, int $userId): bool
    {
        return FeatureFlag::isEnabled($featureKey, $userId);
    }

    /**
     * Create or update a feature flag
     */
    public function set(string $key, string $name, bool $isActive = false, ?array $rules = null): FeatureFlag
    {
        return FeatureFlag::updateOrCreate(
            ['key' => $key],
            [
                'name' => $name,
                'is_active' => $isActive,
                'rules' => $rules,
                'activated_at' => $isActive ? now() : null,
            ]
        );
    }

    /**
     * Enable a feature for everyone
     */
    public function enable(string $key): bool
    {
        $flag = FeatureFlag::where('key', $key)->first();
        if ($flag) {
            $flag->enable();
            return true;
        }
        return false;
    }

    /**
     * Disable a feature
     */
    public function disable(string $key): bool
    {
        $flag = FeatureFlag::where('key', $key)->first();
        if ($flag) {
            $flag->disable();
            return true;
        }
        return false;
    }

    /**
     * Enable feature for specific users
     */
    public function enableForUsers(string $key, array $userIds): bool
    {
        $flag = FeatureFlag::where('key', $key)->first();
        if ($flag) {
            $rules = $flag->rules ?? [];
            $rules['users'] = array_unique(array_merge($rules['users'] ?? [], $userIds));
            $flag->update(['rules' => $rules, 'is_active' => true]);
            return true;
        }
        return false;
    }

    /**
     * Enable feature for specific roles
     */
    public function enableForRoles(string $key, array $roles): bool
    {
        $flag = FeatureFlag::where('key', $key)->first();
        if ($flag) {
            $rules = $flag->rules ?? [];
            $rules['roles'] = array_unique(array_merge($rules['roles'] ?? [], $roles));
            $flag->update(['rules' => $rules, 'is_active' => true]);
            return true;
        }
        return false;
    }

    /**
     * Gradual rollout - enable for percentage of users
     */
    public function rolloutPercentage(string $key, int $percentage): bool
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException('Percentage must be between 0 and 100');
        }

        $flag = FeatureFlag::where('key', $key)->first();
        if ($flag) {
            $rules = $flag->rules ?? [];
            $rules['percentage'] = $percentage;
            $flag->update(['rules' => $rules, 'is_active' => true]);
            return true;
        }
        return false;
    }

    /**
     * Get all active features
     */
    public function getActiveFeatures(): array
    {
        return Cache::remember('active_features', 600, function () {
            return FeatureFlag::active()->pluck('key')->toArray();
        });
    }

    /**
     * Get all features with their status
     */
    public function getAllFeatures(): array
    {
        return FeatureFlag::orderBy('name')->get()->map(function ($flag) {
            return [
                'key' => $flag->key,
                'name' => $flag->name,
                'is_active' => $flag->is_active,
                'rules' => $flag->rules,
                'activated_at' => $flag->activated_at?->toDateTimeString(),
            ];
        })->toArray();
    }

    /**
     * Clear feature flag cache
     */
    public function clearCache(?string $key = null): void
    {
        if ($key) {
            Cache::forget("feature_flag:{$key}");
        } else {
            Cache::forget('active_features');
        }
    }
}
