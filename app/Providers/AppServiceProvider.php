<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Responses\LoginResponse as CustomLoginResponse;

// Models
use App\Models\ImportOperation;
use App\Models\ExportOperationDetailed;
use App\Models\ManufacturingOperation;
use App\Models\CustomsOperation;
use App\Models\ContainerOperation;
use App\Models\AgentOperationDetailed;
use App\Models\User;
use App\Models\Article;
use App\Models\Media;
use App\Models\Broker;
use App\Models\ClearanceJob;
use App\Models\ContainerQuote;
use App\Models\CostCalculation;

// Policies
use App\Policies\ImportOperationPolicy;
use App\Policies\ManufacturingOperationPolicy;
use App\Policies\CustomsOperationPolicy;
use App\Policies\ContainerOperationPolicy;
use App\Policies\UserPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\MediaPolicy;
use App\Policies\BrokerPolicy;
use App\Policies\ClearanceJobPolicy;
use App\Policies\ContainerQuotePolicy;
use App\Policies\CostCalculationPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        ImportOperation::class => ImportOperationPolicy::class,
        ManufacturingOperation::class => ManufacturingOperationPolicy::class,
        CustomsOperation::class => CustomsOperationPolicy::class,
        ContainerOperation::class => ContainerOperationPolicy::class,
        User::class => UserPolicy::class,
        Article::class => ArticlePolicy::class,
        Media::class => MediaPolicy::class,
        Broker::class => BrokerPolicy::class,
        ClearanceJob::class => ClearanceJobPolicy::class,
        ContainerQuote::class => ContainerQuotePolicy::class,
        CostCalculation::class => CostCalculationPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponse::class, CustomLoginResponse::class);
        
        // Register SMS Sender interface (Stub for now, replace with real implementation in production)
        $this->app->bind(
            \App\Contracts\SmsSenderInterface::class,
            \App\Services\StubSmsSender::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ثبّت الجذر لروابط route() وفق APP_URL
        $appUrl = config('app.url'); // يقرأ قيمة APP_URL من .env
        if (!empty($appUrl)) {
            URL::forceRootUrl($appUrl);

            // لو APP_URL يبدأ بـ https فعّل https للروابط
            $scheme = parse_url($appUrl, PHP_URL_SCHEME);
            if ($scheme === 'https') {
                URL::forceScheme('https');
            }
        }

        // Register Policies
        $this->registerPolicies();

        // Register Gates للوحة التحكم
        $this->registerDashboardGates();
        
        // Register Permission Gates
        $this->registerPermissionGates();
    }

    /**
     * Register policies
     */
    protected function registerPolicies(): void
    {
        // Super admin bypass - يتخطى كل التحققات للأدمن
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Register Permission Gates
     * تسجيل Gates لكل صلاحية في النظام
     */
    protected function registerPermissionGates(): void
    {
        // Container Management Gates
        Gate::define('containers.view', fn($user) => $user->can('containers.view'));
        Gate::define('containers.create', fn($user) => $user->can('containers.create'));
        Gate::define('containers.edit', fn($user) => $user->can('containers.edit'));
        Gate::define('containers.delete', fn($user) => $user->can('containers.delete'));
        Gate::define('containers.quotes', fn($user) => $user->can('containers.quotes'));
        Gate::define('containers.booking', fn($user) => $user->can('containers.booking'));
        Gate::define('containers.tracking', fn($user) => $user->can('containers.tracking'));

        // Brokers Management Gates
        Gate::define('brokers.view', fn($user) => $user->can('brokers.view'));
        Gate::define('brokers.create', fn($user) => $user->can('brokers.create'));
        Gate::define('brokers.edit', fn($user) => $user->can('brokers.edit'));
        Gate::define('brokers.delete', fn($user) => $user->can('brokers.delete'));
        Gate::define('brokers.search', fn($user) => $user->can('brokers.search'));
        Gate::define('brokers.review', fn($user) => $user->can('brokers.review'));
        Gate::define('brokers.approve', fn($user) => $user->can('brokers.approve'));

        // Clearance Jobs Gates
        Gate::define('clearance.view', fn($user) => $user->can('clearance.view'));
        Gate::define('clearance.create', fn($user) => $user->can('clearance.create'));
        Gate::define('clearance.edit', fn($user) => $user->can('clearance.edit'));
        Gate::define('clearance.delete', fn($user) => $user->can('clearance.delete'));
        Gate::define('clearance.approve', fn($user) => $user->can('clearance.approve'));

        // Cost Calculations Gates
        Gate::define('costs.view', fn($user) => $user->can('costs.view'));
        Gate::define('costs.create', fn($user) => $user->can('costs.create'));
        Gate::define('costs.edit', fn($user) => $user->can('costs.edit'));
        Gate::define('costs.delete', fn($user) => $user->can('costs.delete'));
        Gate::define('costs.approve', fn($user) => $user->can('costs.approve'));

        // Trucks Management Gates
        Gate::define('trucks.view', fn($user) => $user->can('trucks.view'));
        Gate::define('trucks.create', fn($user) => $user->can('trucks.create'));
        Gate::define('trucks.edit', fn($user) => $user->can('trucks.edit'));
        Gate::define('trucks.delete', fn($user) => $user->can('trucks.delete'));
        Gate::define('trucks.quotes', fn($user) => $user->can('trucks.quotes'));
        Gate::define('trucks.booking', fn($user) => $user->can('trucks.booking'));
        Gate::define('trucks.tracking', fn($user) => $user->can('trucks.tracking'));

        // Ads Management Gates
        Gate::define('ads.view', fn($user) => $user->can('ads.view'));
        Gate::define('ads.create', fn($user) => $user->can('ads.create'));
        Gate::define('ads.edit', fn($user) => $user->can('ads.edit'));
        Gate::define('ads.delete', fn($user) => $user->can('ads.delete'));
        Gate::define('ads.approve', fn($user) => $user->can('ads.approve'));

        // Articles Management Gates
        Gate::define('articles.view', fn($user) => $user->can('articles.view'));
        Gate::define('articles.create', fn($user) => $user->can('articles.create'));
        Gate::define('articles.edit', fn($user) => $user->can('articles.edit'));
        Gate::define('articles.delete', fn($user) => $user->can('articles.delete'));
        Gate::define('articles.publish', fn($user) => $user->can('articles.publish'));

        // Media Management Gates
        Gate::define('media.view', fn($user) => $user->can('media.view'));
        Gate::define('media.upload', fn($user) => $user->can('media.upload'));
        Gate::define('media.delete', fn($user) => $user->can('media.delete'));
        Gate::define('media.manage', fn($user) => $user->can('media.manage'));

        // Notifications Gates
        Gate::define('notifications.view', fn($user) => $user->can('notifications.view'));
        Gate::define('notifications.send', fn($user) => $user->can('notifications.send'));
        Gate::define('notifications.manage', fn($user) => $user->can('notifications.manage'));

        // Subscriptions Gates
        Gate::define('subscriptions.view', fn($user) => $user->can('subscriptions.view'));
        Gate::define('subscriptions.create', fn($user) => $user->can('subscriptions.create'));
        Gate::define('subscriptions.edit', fn($user) => $user->can('subscriptions.edit'));
        Gate::define('subscriptions.delete', fn($user) => $user->can('subscriptions.delete'));

        // Users Management Gates
        Gate::define('users.view', fn($user) => $user->can('users.view'));
        Gate::define('users.create', fn($user) => $user->can('users.create'));
        Gate::define('users.edit', fn($user) => $user->can('users.edit'));
        Gate::define('users.delete', fn($user) => $user->can('users.delete'));

        // Roles Management Gates
        Gate::define('roles.view', fn($user) => $user->can('roles.view'));
        Gate::define('roles.create', fn($user) => $user->can('roles.create'));
        Gate::define('roles.edit', fn($user) => $user->can('roles.edit'));
        Gate::define('roles.delete', fn($user) => $user->can('roles.delete'));
        Gate::define('roles.assign', fn($user) => $user->can('roles.assign'));

        // Dashboard Gates
        Gate::define('dashboard.view', fn($user) => $user->can('dashboard.view'));
        Gate::define('dashboard.analytics', fn($user) => $user->can('dashboard.analytics'));
        Gate::define('dashboard.reports', fn($user) => $user->can('dashboard.reports'));
        Gate::define('dashboard.export', fn($user) => $user->can('dashboard.export'));
    }

    /**
     * Register Dashboard Gates
     */
    protected function registerDashboardGates(): void
    {
        // Gate للوصول إلى لوحة التحكم
        Gate::define('access-dashboard', function ($user) {
            return $user->hasAnyRole(['admin', 'manager', 'dashboard_user']);
        });

        // Gates للأقسام الستة
        Gate::define('view-import-section', function ($user) {
            return $user->hasAnyRole(['admin', 'manager', 'import_manager', 'import_user']);
        });

        Gate::define('view-export-section', function ($user) {
            return $user->hasAnyRole(['admin', 'manager', 'export_manager', 'export_user']);
        });

        Gate::define('view-manufacturing-section', function ($user) {
            return $user->hasAnyRole(['admin', 'manager', 'manufacturing_manager', 'production_user']);
        });

        Gate::define('view-customs-section', function ($user) {
            return $user->hasAnyRole(['admin', 'manager', 'customs_manager', 'customs_broker']);
        });

        Gate::define('view-containers-section', function ($user) {
            return $user->hasAnyRole(['admin', 'manager', 'shipping_manager', 'logistics_user']);
        });

        Gate::define('view-agents-section', function ($user) {
            return $user->hasAnyRole(['admin', 'manager', 'agent_manager']);
        });

        // Gates للـ KPIs والتحليلات
        Gate::define('view-kpis', function ($user) {
            return $user->hasAnyRole(['admin', 'manager']);
        });

        Gate::define('export-data', function ($user) {
            return $user->hasAnyRole(['admin', 'manager']);
        });

        Gate::define('manage-webhooks', function ($user) {
            return $user->hasAnyRole(['admin', 'developer']);
        });

        // Gate للإعدادات المتقدمة
        Gate::define('manage-dashboard-settings', function ($user) {
            return $user->hasRole('admin');
        });
    }
}
