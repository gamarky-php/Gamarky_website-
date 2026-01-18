<?php

/**
 * Dashboard Routes
 * 
 * Purpose: مسارات لوحة التحكم المتقدمة - 6 أقسام رئيسية + أدوات
 * Middleware: auth, verified, can:access-dashboard
 * Prefix: dashboard.*
 * 
 * Sections:
 * 1. Import Operations (الاستيراد)
 * 2. Export Operations (التصدير)
 * 3. Manufacturing (التصنيع)
 * 4. Customs Clearance (التخليص الجمركي)
 * 5. Container Operations (الحاويات)
 * 6. Agency Operations (الوكالات)
 * 
 * Tools:
 * - Ads Management
 * - Notifications Center
 * - Articles/Blog
 * - Media Library
 * - Subscriptions
 * - Users & Roles
 */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes - لوحة التحكم
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    
    // ========== الصفحة الرئيسية للوحة التحكم ==========
    Route::get('/', \App\Livewire\Dashboard\DashboardHome::class)
        ->middleware('can:access-dashboard')
        ->name('index');

    // ========== KPIs & Analytics API ==========
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/kpis/{section}', function ($section) {
            return response()->json(['section' => $section]);
        })->name('kpis');
    });

    // ========== 1. IMPORT OPERATIONS (الاستيراد) ==========
    Route::prefix('import')->name('import.')->middleware('can:view-import-section')->group(function () {
        // Livewire Dashboard Component
        Route::get('/', \App\Livewire\Import\ImportDashboard::class)->name('index');
        
        // Livewire Cost Calculator Component
        Route::get('/costs/{calculationId?}', \App\Livewire\Import\ImportCostCalculator::class)->name('costs');
        
        Route::get('/quotes', function () {
            return view('dashboard.import.quotes');
        })->name('quotes');
        
        Route::get('/shipments', function () {
            return view('dashboard.import.shipments');
        })->name('shipments');
    });

    // ========== 2. EXPORT OPERATIONS (التصدير) ==========
    Route::prefix('export')->name('export.')->middleware('can:view-export-section')->group(function () {
        // Livewire Dashboard Component
        Route::get('/', \App\Livewire\Export\ExportDashboard::class)->name('index');
        
        // Livewire Cost Calculator Component
        Route::get('/costs/{calculationId?}', \App\Livewire\Export\ExportCostCalculator::class)->name('costs');
        
        Route::get('/markets', function () {
            return view('dashboard.export.markets');
        })->name('markets');
        
        Route::get('/quotes', function () {
            return view('dashboard.export.quotes');
        })->name('quotes');
    });

    // ========== 3. MANUFACTURING (التصنيع) ==========
    Route::prefix('manufacturing')->name('manufacturing.')->middleware('can:view-manufacturing-section')->group(function () {
        // Livewire Dashboard Component
        Route::get('/', \App\Livewire\Manufacturing\ManufacturingDashboard::class)->name('index');
        
        // Livewire Cost Calculator Component
        Route::get('/costs/{calculationId?}', \App\Livewire\Manufacturing\ManufacturingCostCalculator::class)->name('costs');
        
        Route::get('/bom', function () {
            return view('dashboard.manufacturing.bom');
        })->name('bom');
        
        Route::get('/quotes', function () {
            return view('dashboard.manufacturing.quotes');
        })->name('quotes');
    });

    // ========== 4. CUSTOMS CLEARANCE (التخليص الجمركي) ==========
    Route::prefix('clearance')->name('clearance.')->middleware('can:view-customs-section')->group(function () {
        // Livewire BrokerFinder Component
        Route::get('/brokers', \App\Livewire\Clearance\BrokerFinder::class)->name('brokers');
        
        // Livewire BrokerProfile Component
        Route::get('/broker/{brokerId}', \App\Livewire\Clearance\BrokerProfile::class)->name('broker');
        
        // Livewire ClearanceTimeline Component (List View)
        Route::get('/timeline', \App\Livewire\Clearance\ClearanceTimeline::class)->name('timeline');
        
        // Livewire ClearanceTimeline Component (Single Job View)
        Route::get('/timeline/{jobId}', \App\Livewire\Clearance\ClearanceTimeline::class)->name('timeline.job');
        
        Route::get('/', function () {
            return view('dashboard.clearance.index');
        })->name('index');
        
        Route::get('/costs', function () {
            return view('dashboard.clearance.costs');
        })->name('costs');
        
        Route::get('/pending', function () {
            return view('dashboard.clearance.pending');
        })->name('pending');
    });

    // ========== 5. CONTAINER OPERATIONS (الحاويات) ==========
    Route::prefix('containers')->name('containers.')->middleware('can:view-containers-section')->group(function () {
        Route::get('/', function () {
            return view('dashboard.containers.index');
        })->name('index');
        
        Route::get('/costs', function () {
            return view('dashboard.containers.costs');
        })->name('costs');
        
        Route::get('/bookings', function () {
            return view('dashboard.containers.bookings');
        })->name('bookings');
        
        Route::get('/tracking', function () {
            return view('dashboard.containers.tracking');
        })->name('tracking');
    });

    // ========== 6. AGENCY OPERATIONS (الوكالات) ==========
    Route::prefix('agency')->name('agency.')->middleware('can:view-agents-section')->group(function () {
        Route::get('/', \App\Livewire\Agency\AgencyDashboard::class)->name('index');
        
        Route::get('/commission-calculator', \App\Livewire\Agency\AgencyCommissionCalculator::class)->name('commission-calculator');
    });

    // ========== TOOLS & UTILITIES ==========
    
    // Analytics & Charts
    Route::prefix('analytics')->name('analytics.')->middleware('can:view-analytics')->group(function () {
        Route::get('/', \App\Livewire\Analytics\AnalyticsDashboard::class)->name('index');
        Route::get('/chart-types', \App\Livewire\Analytics\ChartTypesDemo::class)->name('chart-types');
    });
    
    // Ads Management
    Route::prefix('ads')->name('ads.')->middleware('can:manage-dashboard-settings')->group(function () {
        Route::get('/', \App\Livewire\Shared\AdsManager::class)->name('index');
    });

    // Notifications Center
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', \App\Livewire\Shared\NotificationsCenter::class)->name('index');
        Route::get('/settings', \App\Livewire\Dashboard\NotificationsSettings::class)->name('settings');
    });

    // Articles/Blog Management
    Route::prefix('articles')->name('articles.')->middleware('can:manage-dashboard-settings')->group(function () {
        Route::get('/', \App\Livewire\Shared\ArticlesEditor::class)->name('index');
    });

    // Media Library
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', \App\Livewire\Shared\MediaLibrary::class)->name('index');
    });

    // Subscriptions Management
    Route::prefix('subscriptions')->name('subscriptions.')->middleware('can:manage-dashboard-settings')->group(function () {
        Route::get('/', function () {
            return view('dashboard.subscriptions.index');
        })->name('index');
    });

    // Users Management
    Route::prefix('users')->name('users.')->middleware('can:manage-users')->group(function () {
        Route::get('/', \App\Livewire\Shared\UsersRolesMatrix::class)->name('index');
    });

    // Roles & Permissions
    Route::prefix('roles')->name('roles.')->middleware('can:manage-roles')->group(function () {
        Route::get('/', function () {
            return view('dashboard.roles.index');
        })->name('index');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->middleware('can:manage-dashboard-settings')->group(function () {
        Route::get('/', function () {
            return view('dashboard.settings.index');
        })->name('index');
    });
});