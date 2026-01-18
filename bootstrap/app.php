<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'locale' => \App\Http\Middleware\LocaleMiddleware::class,
            'auth.sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'cors.mobile' => \App\Http\Middleware\MobileCorsMiddleware::class,
        ]);
        
        // Apply locale middleware to all web and api routes
        $middleware->web(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
        ]);
        
        $middleware->api(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
            \App\Http\Middleware\TrackApiUsage::class,
        ]);
        
        // Enable CORS for API routes (Mobile-optimized)
        $middleware->api(prepend: [
            \App\Http\Middleware\MobileCorsMiddleware::class,
        ]);
        
        // API Rate Limiting (60 requests per minute)
        $middleware->throttleApi('60,1');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
