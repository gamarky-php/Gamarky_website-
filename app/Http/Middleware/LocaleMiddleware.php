<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Priority:
     * 1. Session locale
     * 2. User's preferred locale (if authenticated and column exists)
     * 3. Fallback to config default
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get available locales from config
        $availableLocales = array_keys(config('locales.available', ['ar' => [], 'zh' => [], 'en' => []]));
        
        // PRIORITY 1: Session locale
        $locale = session('locale');
        
        // PRIORITY 2: User's preferred locale (if authenticated and column exists)
        if (!$locale && auth()->check()) {
            $user = auth()->user();
            // Safe check: only read user->locale if column exists
            if ($this->hasLocaleColumn() && isset($user->locale)) {
                $locale = $user->locale;
            }
        }
        
        // PRIORITY 3: Fallback to config default
        if (!$locale) {
            $locale = config('locales.default', config('app.locale', 'ar'));
        }
        
        // Validate locale is allowed
        if (!in_array($locale, $availableLocales)) {
            $locale = 'ar'; // Force Arabic if invalid
        }
        
        // Set application locale
        app()->setLocale($locale);
        
        // Get locale metadata from config
        $locales = config('locales.available', []);
        $currentDir = $locales[$locale]['dir'] ?? ($locale === 'ar' ? 'rtl' : 'ltr');
        $currentFlag = $locales[$locale]['flag'] ?? '🇸🇦';
        $currentName = $locales[$locale]['native'] ?? 'العربية';
        
        // Share with all views (blade templates)
        view()->share([
            'currentLocale' => $locale,
            'currentDir' => $currentDir,
            'currentLang' => $locale,
            'currentFlag' => $currentFlag,
            'currentName' => $currentName,
            'availableLocales' => $locales,
        ]);
        
        return $next($request);
    }

    /**
     * Check if users table has locale column
     * (cached to avoid repeated schema checks)
     *
     * @return bool
     */
    private function hasLocaleColumn(): bool
    {
        static $hasColumn = null;
        
        if ($hasColumn === null) {
            try {
                $hasColumn = Schema::hasColumn('users', 'locale');
            } catch (\Exception $e) {
                $hasColumn = false; // Safe fallback if DB not ready
            }
        }
        
        return $hasColumn;
    }
}
