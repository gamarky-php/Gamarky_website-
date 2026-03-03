<?php

/**
 * ============================================
 * Locale Helper Functions
 * ============================================
 * Global helpers for locale operations
 * Works everywhere: controllers, views, PDFs, services
 */

if (! function_exists('locale_dir')) {
    /**
     * Get current text direction (rtl or ltr)
     *
     * @param string|null $locale
     * @return string
     */
    function locale_dir(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return config("locales.available.{$locale}.dir", 'ltr');
    }
}

if (! function_exists('is_rtl')) {
    /**
     * Check if current locale is RTL
     *
     * @param string|null $locale
     * @return bool
     */
    function is_rtl(?string $locale = null): bool
    {
        return locale_dir($locale) === 'rtl';
    }
}

if (! function_exists('is_ltr')) {
    /**
     * Check if current locale is LTR
     *
     * @param string|null $locale
     * @return bool
     */
    function is_ltr(?string $locale = null): bool
    {
        return locale_dir($locale) === 'ltr';
    }
}

if (! function_exists('locale_font')) {
    /**
     * Get appropriate font family for current locale
     *
     * @param string|null $locale
     * @return string
     */
    function locale_font(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return config("locales.fonts.{$locale}", config('locales.fonts.default', 'DejaVu Sans'));
    }
}

if (! function_exists('locale_name')) {
    /**
     * Get locale native name
     *
     * @param string|null $locale
     * @return string
     */
    function locale_name(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return config("locales.available.{$locale}.native", '');
    }
}

if (! function_exists('locale_flag')) {
    /**
     * Get locale flag emoji
     *
     * @param string|null $locale
     * @return string
     */
    function locale_flag(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return config("locales.available.{$locale}.flag", '🌐');
    }
}

if (! function_exists('available_locales')) {
    /**
     * Get all available locales
     *
     * @return array
     */
    function available_locales(): array
    {
        return array_keys(config('locales.available', []));
    }
}
