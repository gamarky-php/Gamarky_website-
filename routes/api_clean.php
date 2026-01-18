<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// V1 API Controllers (Active)
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\Api\V1\PortController;
use App\Http\Controllers\Api\V1\ShippingTypeController;
use App\Http\Controllers\Api\V1\CostCalculatorController;
use App\Http\Controllers\Api\V1\TokenController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1 (Mobile App Ready)
|--------------------------------------------------------------------------
|
| هذه المجموعة من الـ API endpoints مخصصة للتطبيق المحمول
| جميع الـ endpoints هنا تستخدم versioning مع prefix 'v1'
|
*/

// API Version 1 Routes
Route::prefix('v1')->name('api.v1.')->group(function () {
    
    // Authentication Routes (Public)
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
        
        // Authenticated auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('logout-all');
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::put('/update-profile', [AuthController::class, 'updateProfile'])->name('update-profile');
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        });
    });

    // Public Calculator Routes
    Route::prefix('calculator')->name('calculator.')->group(function () {
        Route::get('/countries', [CountryController::class, 'select2'])->name('countries');
        Route::get('/ports', [PortController::class, 'byCountryAndMode'])->name('ports');
        Route::get('/shipping-types', [ShippingTypeController::class, 'select2'])->name('shipping-types');
    });

    // Authenticated Routes
    Route::middleware('auth:sanctum')->group(function () {
        
        // User Management
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/profile', [UserController::class, 'profile'])->name('profile');
            Route::put('/profile', [UserController::class, 'updateProfile'])->name('update-profile');
            Route::post('/upload-avatar', [UserController::class, 'uploadAvatar'])->name('upload-avatar');
            Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
            Route::put('/notifications/{id}/read', [UserController::class, 'markNotificationRead'])->name('mark-notification-read');
        });

        // Cost Calculator
        Route::prefix('costs')->name('costs.')->group(function () {
            Route::post('/calculate', [CostCalculatorController::class, 'calculate'])->name('calculate');
            Route::get('/saved', [CostCalculatorController::class, 'getSaved'])->name('saved');
            Route::get('/{id}', [CostCalculatorController::class, 'getById'])->name('show');
            Route::delete('/{id}', [CostCalculatorController::class, 'delete'])->name('delete');
            Route::get('/{id}/pdf', [CostCalculatorController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/{id}/excel', [CostCalculatorController::class, 'exportExcel'])->name('export.excel');
        });

        // Token Management
        Route::prefix('tokens')->name('tokens.')->group(function () {
            Route::get('/', [TokenController::class, 'index'])->name('index');
            Route::delete('/{tokenId}', [TokenController::class, 'destroy'])->name('destroy');
            Route::delete('/', [TokenController::class, 'destroyOthers'])->name('destroy-others');
            Route::get('/verify', [TokenController::class, 'verify'])->name('verify');
            Route::post('/refresh', [TokenController::class, 'refresh'])->name('refresh');
            Route::get('/stats', [TokenController::class, 'stats'])->name('stats');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Test Route for API Health Check
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'Gamarky API V1 is running',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
})->name('api.health');