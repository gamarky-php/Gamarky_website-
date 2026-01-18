<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\FeatureFlag;

class FeatureFlagServiceProvider extends ServiceProvider
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
        // Blade directive for feature flags
        Blade::if('feature', function (string $featureKey) {
            return FeatureFlag::isEnabled($featureKey, auth()->id());
        });

        // Alternative syntax
        Blade::directive('featureEnabled', function ($expression) {
            return "<?php if(\\App\\Models\\FeatureFlag::isEnabled({$expression}, auth()->id())): ?>";
        });

        Blade::directive('endfeatureEnabled', function () {
            return '<?php endif; ?>';
        });
    }
}
