<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Cache analytics data with configurable TTL
     * 
     * @param string $key Cache key
     * @param callable $callback Data retrieval callback
     * @param int $minutes Cache duration in minutes (default: 10)
     * @return mixed
     */
    public function rememberAnalytics(string $key, callable $callback, int $minutes = 10)
    {
        return Cache::remember("analytics:{$key}", now()->addMinutes($minutes), $callback);
    }

    /**
     * Cache config data (longer TTL)
     * 
     * @param string $key Cache key
     * @param callable $callback Data retrieval callback
     * @param int $hours Cache duration in hours (default: 24)
     * @return mixed
     */
    public function rememberConfig(string $key, callable $callback, int $hours = 24)
    {
        return Cache::remember("config:{$key}", now()->addHours($hours), $callback);
    }

    /**
     * Cache routes data
     * 
     * @param string $key Cache key
     * @param callable $callback Data retrieval callback
     * @return mixed
     */
    public function rememberRoutes(string $key, callable $callback)
    {
        return Cache::rememberForever("routes:{$key}", $callback);
    }

    /**
     * Invalidate analytics cache
     * 
     * @param string|null $key Specific key or all analytics cache
     * @return bool
     */
    public function forgetAnalytics(?string $key = null): bool
    {
        if ($key) {
            return Cache::forget("analytics:{$key}");
        }

        // Clear all analytics cache
        return Cache::flush(); // Or use tags if driver supports it
    }

    /**
     * Invalidate config cache
     * 
     * @param string|null $key Specific key or all config cache
     * @return bool
     */
    public function forgetConfig(?string $key = null): bool
    {
        if ($key) {
            return Cache::forget("config:{$key}");
        }

        return true;
    }

    /**
     * Clear all application cache
     * 
     * @return bool
     */
    public function clearAll(): bool
    {
        return Cache::flush();
    }

    /**
     * Get cache statistics
     * 
     * @return array
     */
    public function getStats(): array
    {
        return [
            'driver' => config('cache.default'),
            'prefix' => config('cache.prefix'),
            'analytics_ttl' => '5-15 minutes',
            'config_ttl' => '24 hours',
            'routes_ttl' => 'forever',
        ];
    }

    /**
     * Cache with tags (if driver supports it)
     * 
     * @param array $tags
     * @param string $key
     * @param callable $callback
     * @param int $minutes
     * @return mixed
     */
    public function rememberWithTags(array $tags, string $key, callable $callback, int $minutes = 10)
    {
        // Check if driver supports tags (Redis, Memcached)
        if (in_array(config('cache.default'), ['redis', 'memcached'])) {
            return Cache::tags($tags)->remember($key, now()->addMinutes($minutes), $callback);
        }

        // Fallback to regular cache
        return Cache::remember($key, now()->addMinutes($minutes), $callback);
    }

    /**
     * Flush cache by tags
     * 
     * @param array $tags
     * @return bool
     */
    public function flushTags(array $tags): bool
    {
        if (in_array(config('cache.default'), ['redis', 'memcached'])) {
            Cache::tags($tags)->flush();
            return true;
        }

        return false;
    }
}
