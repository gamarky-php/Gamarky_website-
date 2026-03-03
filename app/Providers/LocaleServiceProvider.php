<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share global locale data with all views
        $this->shareGlobalLocaleData();

        // Register Blade Directives for i18n
        $this->registerBladeDirectives();

        // Register Helper Macros
        $this->registerHelperMacros();
    }

    /**
     * Share global locale data with all views
     */
    protected function shareGlobalLocaleData(): void
    {
        View::share([
            'currentLocale' => app()->getLocale(),
            'currentDir' => locale_dir(),
            'availableLocales' => available_locales(),
            'localeNames' => collect(config('locales.available', []))
                ->mapWithKeys(fn($data, $code) => [$code => $data['native']])
                ->all(),
        ]);
    }

    /**
     * Register custom Blade directives for i18n
     */
    protected function registerBladeDirectives(): void
    {
        /**
         * @locale - Get current locale
         * Usage: <html lang="@locale">
         */
        Blade::directive('locale', function () {
            return "<?php echo app()->getLocale(); ?>";
        });

        /**
         * @dir - Get current direction (rtl or ltr)
         * Usage: <html dir="@dir">
         */
        Blade::directive('dir', function () {
            return "<?php echo config('locales.available.' . app()->getLocale() . '.dir', 'ltr'); ?>";
        });

        /**
         * @isRtl - Check if current locale is RTL
         * Usage: @isRtl ... @endisRtl
         */
        Blade::if('isRtl', function () {
            return config('locales.available.' . app()->getLocale() . '.dir', 'ltr') === 'rtl';
        });

        /**
         * @isLtr - Check if current locale is LTR
         * Usage: @isLtr ... @endisLtr
         */
        Blade::if('isLtr', function () {
            return config('locales.available.' . app()->getLocale() . '.dir', 'ltr') === 'ltr';
        });

        /**
         * @localeIs - Check if current locale matches
         * Usage: @localeIs('ar') ... @endlocaleIs
         */
        Blade::if('localeIs', function ($locale) {
            return app()->getLocale() === $locale;
        });

        /**
         * @localeIsNot - Check if current locale doesn't match
         * Usage: @localeIsNot('ar') ... @endlocaleIsNot
         */
        Blade::if('localeIsNot', function ($locale) {
            return app()->getLocale() !== $locale;
        });

        /**
         * @rtlClass - Output RTL/LTR specific class
         * Usage: class="@rtlClass('mr-4', 'ml-4')"
         */
        Blade::directive('rtlClass', function ($expression) {
            return "<?php echo config('locales.available.' . app()->getLocale() . '.dir', 'ltr') === 'rtl' ? ({$expression})[0] : ({$expression})[1] ?? ''; ?>";
        });

        /**
         * @flag - Get current locale flag emoji
         * Usage: @flag
         */
        Blade::directive('flag', function () {
            return "<?php echo config('locales.available.' . app()->getLocale() . '.flag', '🌐'); ?>";
        });

        /**
         * @localeName - Get current locale native name
         * Usage: @localeName
         */
        Blade::directive('localeName', function () {
            return "<?php echo config('locales.available.' . app()->getLocale() . '.native', 'العربية'); ?>";
        });
    }

    /**
     * Register helper macros for Str and Carbon
     */
    protected function registerHelperMacros(): void
    {
        // You can add String/Carbon macros here if needed
        // Example:
        // Str::macro('localeDirection', function () {
        //     return config('locales.available.' . app()->getLocale() . '.dir', 'ltr');
        // });
    }
}
