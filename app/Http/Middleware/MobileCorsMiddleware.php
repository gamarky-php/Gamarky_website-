<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MobileCorsMiddleware
{
    /**
     * Handle an incoming request for mobile applications.
     */
    public function handle(Request $request, Closure $next)
    {
        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            return $this->handlePreflightRequest($request);
        }

        $response = $next($request);

        // Add CORS headers to the response
        return $this->addCorsHeaders($request, $response);
    }

    /**
     * Handle preflight OPTIONS request
     */
    private function handlePreflightRequest(Request $request)
    {
        return response('', 200)
            ->header('Access-Control-Allow-Origin', $this->getAllowedOrigin($request))
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type, Accept, Origin, X-Requested-With')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Max-Age', '86400'); // 24 hours
    }

    /**
     * Add CORS headers to response
     */
    private function addCorsHeaders(Request $request, $response)
    {
        $origin = $this->getAllowedOrigin($request);

        return $response
            ->header('Access-Control-Allow-Origin', $origin)
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Expose-Headers', 'Content-Length, Content-Type');
    }

    /**
     * Get allowed origin based on request
     */
    private function getAllowedOrigin(Request $request)
    {
        $origin = $request->header('Origin');
        
        if (!$origin) {
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
        if (app()->environment('local') && strpos($origin, 'localhost') !== false) {
            return $origin;
        }

        return '*';
    }
}