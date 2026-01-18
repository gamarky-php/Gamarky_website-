<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\FeatureFlag;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureFlag
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        if (!FeatureFlag::isEnabled($featureKey, auth()->id())) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This feature is not available yet.',
                ], 403);
            }

            abort(403, 'This feature is not available yet.');
        }

        return $next($request);
    }
}
