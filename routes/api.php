<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{AuthController, HomeController, ImportController};

// Legacy controllers (webhooks only)
use App\Http\Controllers\Api\WebhookController;

// Payment & Journey Controllers
use App\Http\Controllers\Api\PaymobWebhookController;
use App\Http\Controllers\Api\V1\JourneyController;
use App\Http\Controllers\Auth\SocialLoginController;

/*
|--------------------------------------------------------------------------
| API Routes - Health Check
|--------------------------------------------------------------------------
| لازم يرجع JSON للتأكد إن السيرفر بيرد API مش صفحة.
*/
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

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

    // توثيق (بدون Middleware - API عام)
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']); // إضافة جديدة
    Route::post('/auth/google/mobile', [SocialLoginController::class, 'mobileGoogleLogin']);

    // محمي بالـtoken
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // === Journey / Pay-per-Journey API ===
        Route::prefix('journeys')->name('api.journeys.')->group(function () {
            // List user journeys
            Route::get('/', [JourneyController::class, 'index'])->name('index');

            // Create new journey (draft)
            Route::post('/', [JourneyController::class, 'store'])->name('store');

            // Get journey details (with items, payments, status)
            Route::get('/{journey}', [JourneyController::class, 'show'])->name('show');

            // Update journey (draft only)
            Route::put('/{journey}', [JourneyController::class, 'update'])->name('update');

            // Checkout journey (create payment intent)
            Route::post('/{journey}/checkout', [JourneyController::class, 'checkout'])->name('checkout');

            // Cancel journey
            Route::post('/{journey}/cancel', [JourneyController::class, 'cancel'])->name('cancel');

            // Journey items management
            Route::post('/{journey}/items', [JourneyController::class, 'addItem'])->name('items.add');
            Route::delete('/{journey}/items/{item}', [JourneyController::class, 'removeItem'])->name('items.remove');
        });

        // My active journeys shortcut
        Route::get('/me/journeys', [JourneyController::class, 'myJourneys'])->name('api.me.journeys');

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

    // === Paymob Payment Webhook ===
    // This endpoint receives payment updates from Paymob (Egypt)
    Route::post('/paymob/transaction', [PaymobWebhookController::class, 'handleTransaction'])->name('paymob.transaction');

    // Response callback (when user returns from payment page)
    Route::get('/paymob/response', [PaymobWebhookController::class, 'handleResponse'])->name('paymob.response');

    // Health check for Paymob webhook
    Route::get('/paymob/health', [PaymobWebhookController::class, 'health'])->name('paymob.health');
});
