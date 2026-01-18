<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SocialAuthDebugger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log social authentication attempts for debugging
        if ($request->routeIs('auth.social.*')) {
            Log::info('Social Auth Request', [
                'route' => $request->route()->getName(),
                'provider' => $request->route('provider'),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'session_id' => $request->session()->getId(),
            ]);
        }

        $response = $next($request);

        // Log any errors or redirects
        if ($request->routeIs('auth.social.*')) {
            Log::info('Social Auth Response', [
                'status_code' => $response->getStatusCode(),
                'redirect_url' => $response->headers->get('Location'),
            ]);
        }

        return $response;
    }
}
