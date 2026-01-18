<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialLogonController;
use App\Http\Controllers\Auth\PhoneVerificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ManufacturingController;
use App\Http\Controllers\Admin\CustomsController;
use App\Http\Controllers\Admin\ContainersController;
use App\Http\Controllers\Admin\AgentsController;
use App\Http\Controllers\Admin\SuppliersController;

// Supplier Controllers
use App\Http\Controllers\SuppliersController as PublicSuppliersController;
use App\Http\Controllers\Admin\SupplierImportController;

// Public Controllers
use App\Http\Controllers\AdsController;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

/*
|--------------------------------------------------------------------------
| Web Routes - Gamarky Application
|--------------------------------------------------------------------------
| بنية منظمة للمسارات:
| 1. الصفحة الرئيسية
| 2. الأقسام الأمامية (Frontend) - 6 أقسام رئيسية
| 3. مسارات المصادقة (Auth)
| 4. مسارات الإدارة (Admin)
| 5. مسارات إضافية
*/

// TEST ROUTE - REMOVE AFTER DEBUGGING
Route::get('/test-dashboard', function () {
    return view('test-dashboard');
});

/*
|==========================================================================
| PUBLIC ROUTES - الراوتات العامة (الواجهة الأمامية)
|==========================================================================
*/

// ================= PUBLIC (واجهة) =================
Route::get('/', fn () => view('front.home'))->name('front.home');

// ================= LANGUAGE SWITCHER =================
Route::get('/lang/{locale}', function ($locale) {
    // Get available locales from config
    $availableLocales = array_keys(config('locales.available', ['ar' => [], 'zh' => [], 'en' => []]));
    
    // Validate locale
    if (!in_array($locale, $availableLocales)) {
        abort(404, 'Locale not supported');
    }
    
    // Store in session (persist across requests)
    session(['locale' => $locale]);
    app()->setLocale($locale);
    
    // OPTIONAL: Persist to database if user is authenticated AND column exists
    if (auth()->check()) {
        try {
            $user = auth()->user();
            // Only update if locale column exists in users table
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'locale')) {
                $user->locale = $locale;
                $user->save();
            }
        } catch (\Exception $e) {
            // Silent fail - don't break the language switch if DB update fails
            \Illuminate\Support\Facades\Log::warning('Failed to update user locale: ' . $e->getMessage());
        }
    }
    
    return redirect()->back()->with('success', 'تم تغيير اللغة بنجاح');
})->name('locale.switch');

Route::prefix('import')->name('front.import.')->group(function () {
    Route::view('/', 'front.import.index')->name('index');
    Route::view('/calculator', 'front.import.calculator')->name('calculator');
    Route::view('/procedures', 'front.import.procedures')->name('procedures');
    Route::view('/discover', 'front.import.discover')->name('discover');
});

Route::prefix('export')->name('front.export.')->group(function () {
    Route::view('/', 'front.export.index')->name('index');
    Route::view('/calculator', 'front.export.calculator')->name('calculator');
    Route::view('/procedures', 'front.export.procedures')->name('procedures');
    Route::view('/markets', 'front.export.markets')->name('markets'); // إكتشف الأسواق المستهدفة
});

Route::prefix('manufacturing')->name('front.manufacturing.')->group(function () {
    Route::view('/', 'manufacturing.index')->name('index');
    Route::view('/calculator', 'front.manufacturing.calculator')->name('calculator');
    Route::view('/raw-materials', 'front.manufacturing.raw-materials')->name('raw-materials');
});

Route::prefix('customs')->name('front.customs.')->group(function () {
    // ابحث عن مستخلص
    Route::view('/', 'front.clearance.index')->name('index');

    // دور المستخلص
    Route::view('/role', 'front.clearance.role')->name('role');

    // الإشعارات والتقييم
    Route::view('/notifications', 'front.clearance.notifications')->name('notifications');

    // تسجيل مستخلص
    Route::view('/register', 'front.clearance.register')->name('register');
});

// ================= SHIPPING SECTION (بورصة الحاويات والنقل) =================
Route::prefix('shipping')->as('front.shipping.')->group(function () {
    // Container Routes (الحاويات)
    Route::view('/container/quote', 'front.shipping.container-quote')->name('quote');
    Route::view('/container/book',  'front.shipping.container-book')->name('book');
    Route::view('/container/track', 'front.shipping.container-track')->name('track-container');

    // Truck Routes (الشاحنات)
    Route::view('/truck/quote', 'front.shipping.truck-quote')->name('truck-quote');
    Route::view('/truck/book',  'front.shipping.truck-book')->name('book-truck');
    Route::view('/truck/track', 'front.shipping.truck-track')->name('track-truck');
});

// ================= CONTAINERS SECTION (بورصة الحاويات) =================
Route::prefix('containers')->name('front.containers.')->group(function () {
    Route::view('/', 'front.containers.index')->name('index');
    // جاهز للتوسّع لاحقًا (quote/book/track) إن أردت
});

Route::prefix('agent')->name('front.agent.')->group(function () {
    Route::view('/', 'agent.index')->name('index');
    Route::view('/shipping', 'front.agent.shipping')->name('shipping');
    Route::view('/brand', 'front.agent.brand')->name('brand');
});

// ================= SUPPLIERS SECTION (الموردون) =================
Route::prefix('suppliers')->name('front.suppliers.')->group(function () {
    Route::get('/', [PublicSuppliersController::class, 'index'])->name('index');
});

/*
|==========================================================================
| 3. AUTHENTICATION ROUTES - مسارات المصادقة
|==========================================================================
| Include Jetstream/Fortify auth routes if available
*/
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

/*
|---------- 3.1 GUEST ROUTES (Unauthenticated Users) ----------
*/
Route::middleware('guest')->group(function () {

    // Registration
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('register.store');

    // Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    // Password Reset
    Route::get('/forgot-password', function () {
        return Route::has('password.request')
            ? redirect()->route('password.request')
            : view('auth.forgot-password');
    })->name('password.request');
});

/*
|==========================================================================
| 3.2 SOCIAL AUTHENTICATION (OAuth) - التسجيل عبر وسائل التواصل
|==========================================================================
| Google & Apple Sign In routes (outside auth middleware)
*/

// Google OAuth
Route::get('/auth/social/google', [SocialLogonController::class, 'redirect'])
    ->name('auth.google.redirect');

Route::get('/auth/social/google/callback', [SocialLogonController::class, 'callback'])
    ->name('auth.google.callback');

// Apple OAuth (safe stubs - won't break if not configured)
Route::get('/auth/apple/redirect', [SocialLogonController::class, 'appleRedirect'])
    ->name('auth.apple.redirect');

Route::get('/auth/apple/callback', [SocialLogonController::class, 'appleCallback'])
    ->name('auth.apple.callback');

/*
|---------- 3.3 AUTHENTICATED ROUTES (Logged-in Users) ----------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('front.home')->with('success', 'تم تسجيل الخروج بنجاح');
    })->name('logout');

    // Profile
    Route::get('/profile', function () {
        return view('front.profile');
    })->name('front.profile');

    // Dashboard (redirect to admin for admins)
    Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
        ->middleware('verified')
        ->name('dashboard');

    // Email Verification
    Route::view('/email/verify', 'auth.verify-email')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (Request $request) {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            abort(403);
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            abort(403);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended('/?verified=1');
    })->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('status', 'already-verified');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    // Phone Verification
    Route::get('/phone/verify', [PhoneVerificationController::class, 'show'])->name('phone.verify');
    Route::post('/phone/send-code', [PhoneVerificationController::class, 'send'])
        ->name('phone.send')
        ->middleware('throttle:3,1');
    Route::post('/phone/verify', [PhoneVerificationController::class, 'verify'])
        ->name('phone.verify.submit')
        ->middleware('throttle:5,1');
});

/*
|==========================================================================
| PROTECTED ROUTES - مسارات الإدارة (لوحة التحكم فقط)
|==========================================================================
*/

// ================ ADMIN DASHBOARD (للأدمن فقط) ================
Route::prefix('admin')->name('admin.')->middleware(['auth','verified','is_admin'])->group(function () {
    Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('dashboard');

    Route::get('/import',        [\App\Http\Controllers\Admin\ImportController::class,        'index'])->name('import.index');
    Route::get('/export',        [\App\Http\Controllers\Admin\ExportController::class,        'index'])->name('export.index');
    Route::get('/manufacturing', [\App\Http\Controllers\Admin\ManufacturingController::class, 'index'])->name('manufacturing.index');
    Route::get('/customs',       [\App\Http\Controllers\Admin\CustomsController::class,       'index'])->name('customs.index');
    Route::get('/containers',    [\App\Http\Controllers\Admin\ContainersController::class,    'index'])->name('containers.index');
    Route::get('/agents',        [\App\Http\Controllers\Admin\AgentsController::class,        'index'])->name('agents.index');
    
    // Suppliers routes
    Route::get('/suppliers', [SuppliersController::class, 'index'])->name('suppliers.index');
});

/*
|==========================================================================
| 5. PUBLIC API & WIDGETS - API العامة والعناصر
|==========================================================================
*/

// Ads API & Widget
Route::get('/api/ads', [AdsController::class, 'index'])->name('api.ads.index');
Route::get('/ads/widget', [AdsController::class, 'widget'])->name('ads.widget');
Route::get('/ad/{ad}/click', [AdsController::class, 'click'])->name('ads.click');

/*
|==========================================================================
| 6. DASHBOARD ROUTES - مسارات لوحة التحكم المتقدمة
|==========================================================================
| Prefix: dashboard.*
| Middleware: auth, verified, can:access-dashboard
| Sections: Import, Export, Manufacturing, Clearance, Containers, Agency
| Tools: Ads, Notifications, Articles, Media, Subscriptions, Users
*/
if (file_exists(__DIR__.'/dashboard.php')) {
    require __DIR__.'/dashboard.php';
}

/*
|==========================================================================
| 7. DEVELOPMENT & TESTING ROUTES
|==========================================================================
| Only available in local/development environment
*/
if (app()->environment(['local', 'development'])) {
    Route::view('/auth/test', 'auth.test')->name('auth.test');
}

// ====== Fallback لأي مسارات غير معرّفة ======
Route::fallback(function () {
    return redirect()->route('front.home');
});

Route::view('/privacy-policy', 'front.privacy')->name('privacy.policy');
