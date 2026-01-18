<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * All dashboard abilities that need to be registered as gates.
     * Centralized list for easy maintenance.
     *
     * @var array<string>
     */
    protected $dashboardAbilities = [
        'access-dashboard',
        'manage-dashboard-settings',
        'view-import-section',
        'view-export-section',
        'view-analytics',
        'manage-users',
        'manage-roles',
        'view-customs-section',
        'view-containers-section',
        'view-agents-section',
        'view-manufacturing-section',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Global admin bypass: If user is admin, allow everything
        Gate::before(function ($user, $ability) {
            return $user && (int)$user->is_admin === 1 ? true : null;
        });

        // Register all dashboard ability gates
        foreach ($this->dashboardAbilities as $ability) {
            Gate::define($ability, function ($user) use ($ability) {
                return $this->checkUserAbility($user, $ability);
            });
        }
    }

    /**
     * Centralized ability check logic.
     * 
     * Single source of truth:
     * 1. Admin users (is_admin = 1) => allowed (handled by Gate::before)
     * 2. Check Spatie permission if available
     * 3. Deny by default
     *
     * @param \App\Models\User|null $user
     * @param string $ability
     * @return bool
     */
    protected function checkUserAbility($user, string $ability): bool
    {
        // Null user = not authenticated = deny
        if (!$user) {
            return false;
        }

        // Admin check (redundant due to Gate::before, but kept for explicit clarity)
        if ((int)$user->is_admin === 1) {
            return true;
        }

        // Check if Spatie permission system is available and user has the permission
        if (method_exists($user, 'hasPermissionTo')) {
            try {
                return $user->hasPermissionTo($ability, 'web');
            } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                // Permission not seeded yet, deny access
                \Log::warning("Permission not found in database: {$ability}", [
                    'user_id' => $user->id,
                    'hint' => 'Run: php artisan db:seed --class=PermissionsSeeder'
                ]);
                return false;
            } catch (\Exception $e) {
                // Other errors, log and deny
                \Log::error("Permission check error for ability: {$ability}", [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                return false;
            }
        }

        // Fallback: Check Laravel's native can() if available
        if (method_exists($user, 'can')) {
            try {
                return $user->can($ability);
            } catch (\Exception $e) {
                // Continue to deny
            }
        }

        // Deny by default
        return false;
    }

    /**
     * Get all registered dashboard abilities.
     * Useful for seeding permissions or debugging.
     *
     * @return array<string>
     */
    public function getDashboardAbilities(): array
    {
        return $this->dashboardAbilities;
    }
}
