<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'You must be logged in to access this area.');
        }

        $user = auth()->user();

        // Check if user has admin role or is_admin property (fallback)
        $isAdmin = false;

        if (method_exists($user, 'hasRole')) {
            // Using Spatie Permission - check for admin role
            $isAdmin = $user->hasRole('admin') || $user->hasRole('super-admin');
        }

        // Fallback to is_admin property if it exists
        if (!$isAdmin && property_exists($user, 'is_admin')) {
            $isAdmin = $user->is_admin;
        }

        // For development, allow access if user ID is 1 (first user)
        if (!$isAdmin && app()->environment(['local', 'development']) && $user->id === 1) {
            $isAdmin = true;
        }

        if (!$isAdmin) {
            abort(403, 'Unauthorized access to admin area.');
        }

        return $next($request);
    }
}
