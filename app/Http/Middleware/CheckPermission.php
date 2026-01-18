<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     * @param  string|null  $guard
     */
    public function handle(Request $request, Closure $next, string $permission, ?string $guard = null): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول للوصول إلى هذه الصفحة');
        }

        $user = auth()->user();

        // Super admin bypass (has 'admin' role)
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Check if permission is using Gate
        if (Gate::allows($permission)) {
            return $next($request);
        }

        // Check if user has permission via Spatie Permission
        if ($user->can($permission)) {
            return $next($request);
        }

        // Permission denied
        abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
    }
}
