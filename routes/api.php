<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{AuthController, HomeController, ImportController};

// Legacy controllers (webhooks only)
use App\Http\Controllers\Api\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1 (Mobile App Ready)
|--------------------------------------------------------------------------
|
| هذه المجموعة من الـ API endpoints مخصصة للتطبيق المحمول
| جميع الـ endpoints هنا تستخدم versioning مع prefix 'v1'
|
*/

Route::prefix('v1')->group(function () {
    // عام (لا يحتاج توثيق)
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/menus', [HomeController::class, 'menus']); // الأقسام الستة والروابط

    // استيراد (مثال)
    Route::get('/import/calculator/prefill', [ImportController::class, 'prefill']);
    Route::post('/import/calculator/compute', [ImportController::class, 'compute']);

    // توثيق
    Route::post('/auth/login', [AuthController::class, 'login']);

    // محمي بالـtoken
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        // … باقي خدماتك (العروض، الشحنات، …)
    });
});

/*
|--------------------------------------------------------------------------
| Legacy API Routes (Webhooks Only)
|--------------------------------------------------------------------------
*/

// Webhook API routes (external integrations with signature verification)
Route::prefix('webhooks')->name('api.webhooks.')->group(function () {
    // Test endpoint (local only)
    Route::post('/test', [WebhookController::class, 'test'])->name('test');
    
    // Production webhook endpoints
    Route::post('/booking.confirmed', [WebhookController::class, 'bookingConfirmed'])->name('booking.confirmed');
    Route::post('/documents.completed', [WebhookController::class, 'documentsCompleted'])->name('documents.completed');
    Route::post('/tracking.updated', [WebhookController::class, 'trackingUpdated'])->name('tracking.updated');
});

