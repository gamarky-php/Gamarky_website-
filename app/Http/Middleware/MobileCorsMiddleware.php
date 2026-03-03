<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MobileCorsMiddleware
{
    /**
     * Handle an incoming request for mobile applications.
     * Defensive implementation that never throws exceptions.
     */
    public function handle(Request $request, Closure $next)
    {
        // Handle preflight OPTIONS request immediately
        if ($request->isMethod('OPTIONS')) {
            return $this->handlePreflightRequest($request);
        }

        try {
            // Process the request through the middleware chain
            $response = $next($request);
            
            // Add CORS headers to the response
            return $this->addCorsHeaders($request, $response);
        } catch (\Throwable $e) {
            // If anything fails, return 500 with CORS headers to allow error visibility
            $errorResponse = response()->json([
                'message' => 'Internal Server Error',
                'error' => app()->environment('local') ? $e->getMessage() : 'An error occurred'
            ], 500);
            
            return $this->addCorsHeaders($request, $errorResponse);
        }
    }

    /**
     * Handle preflight OPTIONS request.
     * Returns 204 No Content with all necessary CORS headers.
     */
    private function handlePreflightRequest(Request $request)
    {
        $origin = $this->getAllowedOrigin($request);
        
        return response('', 204)
            ->header('Access-Control-Allow-Origin', $origin)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Max-Age', '86400')
            ->header('Vary', 'Origin');
    }

    /**
     * Add CORS headers to any response.
     * Null-safe and defensive implementation.
     */
    private function addCorsHeaders(Request $request, $response)
    {
        // Ensure we have a valid response object
        if (!$response instanceof Response) {
            $response = response('', 500);
        }

        $origin = $this->getAllowedOrigin($request);

        try {
            $response
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Expose-Headers', 'Cache-Control, Content-Language, Content-Type, Expires, Last-Modified, Pragma')
                ->header('Vary', 'Origin');
        } catch (\Throwable $e) {
            // If header addition fails, log but don't crash
            // Response will still be returned without CORS headers
            if (function_exists('logger')) {
                logger()->warning('Failed to add CORS headers', ['error' => $e->getMessage()]);
            }
        }

        return $response;
    }

    /**
     * Get allowed origin based on request.
     * Null-safe: never throws even if Origin header is missing.
     */
    private function getAllowedOrigin(Request $request)
    {
        try {
            $origin = $request->header('Origin');
            
            // If no origin header, return wildcard
            if (!$origin || !is_string($origin)) {
                return '*';
            }

            // List of allowed origins for mobile development
            $allowedOrigins = [
                // Local development
                'http://localhost',
                'http://127.0.0.1',
                
                // Mobile development
                'http://10.0.2.2', // Android emulator
                
                // Capacitor/Cordova
                'capacitor://localhost',
                'ionic://localhost',
                'http://localhost:8080',
                'http://localhost:8100', // Ionic serve
                
                // React Native
                'http://localhost:8081',
                'http://localhost:19000', // Expo
                'http://localhost:19006', // Expo web
            ];

            // Check if origin matches any allowed patterns
            foreach ($allowedOrigins as $allowedOrigin) {
                if (strpos($origin, $allowedOrigin) === 0) {
                    return $origin;
                }
            }

            // Check for local network IPs (192.168.x.x, 10.x.x.x)
            if (preg_match('/^https?:\/\/(192\.168\.\d+\.\d+|10\.\d+\.\d+\.\d+)(:\d+)?$/', $origin)) {
                return $origin;
            }

            // For development, allow all localhost variations
            if (function_exists('app') && app()->environment('local') && strpos($origin, 'localhost') !== false) {
                return $origin;
            }

            // Default: allow all (permissive for API)
            return '*';
        } catch (\Throwable $e) {
            // If anything fails, return wildcard for maximum compatibility
            return '*';
        }
    }
}