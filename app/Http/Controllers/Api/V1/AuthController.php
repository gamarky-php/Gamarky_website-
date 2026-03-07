<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * تسجيل دخول المستخدم
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'device_name' => 'string|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات الدخول غير صحيحة'
            ], 401);
        }

        // حذف الـ tokens القديمة للجهاز نفسه
        $deviceName = $request->device_name ?? 'mobile-app';
        $user->tokens()->where('name', $deviceName)->delete();
        
        // إنشاء token جديد مع صلاحية 60 يوم
        $token = $user->createToken($deviceName, ['*'], now()->addDays(60))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * تسجيل مستخدم جديد
     * POST /api/v1/auth/register
     */
    public function register(Request $request)
    {
        Log::info('[Auth][Register] Request received', [
            'ip'          => $request->ip(),
            'email'       => $request->email,
            'has_phone'   => !empty($request->phone),
            'has_country' => !empty($request->country),
            'device_name' => $request->device_name,
        ]);

        // ─── Validation ────────────────────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255',
            'password'              => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password',
            'phone'                 => 'nullable|string|max:20',
            'country'               => 'nullable|string|max:100',
            'device_name'           => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::warning('[Auth][Register] Validation failed', [
                'email'  => $request->email,
                'errors' => $validator->errors()->toArray(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ─── Duplicate checks (explicit 409 before DB insert) ──────────────────────
        if (User::where('email', $request->email)->exists()) {
            Log::warning('[Auth][Register] Duplicate email attempt', ['email' => $request->email]);

            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني مستخدم بالفعل',
                'errors'  => ['email' => ['البريد الإلكتروني مستخدم بالفعل']],
            ], 409);
        }

        if ($request->filled('phone') && User::where('phone', $request->phone)->exists()) {
            Log::warning('[Auth][Register] Duplicate phone attempt', ['phone' => $request->phone]);

            return response()->json([
                'success' => false,
                'message' => 'رقم الهاتف مستخدم بالفعل',
                'errors'  => ['phone' => ['رقم الهاتف مستخدم بالفعل']],
            ], 409);
        }

        // ─── Create user ───────────────────────────────────────────────────────────
        try {
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'phone'             => $request->phone ?? null,
                'country'           => $request->country ?? null,
                'email_verified_at' => now(),
            ]);

            Log::info('[Auth][Register] User created successfully', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'name'    => $user->name,
            ]);

            // ─── Issue Sanctum token ────────────────────────────────────────────────
            $deviceName = $request->device_name ?? 'mobile-app';
            $token      = $user->createToken($deviceName, ['*'], now()->addDays(60))->plainTextToken;

            Log::info('[Auth][Register] Token issued', [
                'user_id'     => $user->id,
                'device_name' => $deviceName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الحساب بنجاح',
                'data'    => [
                    'user'       => new UserResource($user),
                    'token'      => $token,
                    'token_type' => 'Bearer',
                ],
            ], 201);

        } catch (\Illuminate\Database\QueryException $e) {
            // Race-condition duplicate (MySQL error 1062)
            if (($e->errorInfo[1] ?? null) === 1062) {
                Log::warning('[Auth][Register] DB duplicate entry (race condition)', [
                    'email' => $request->email,
                    'error' => $e->getMessage(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'البريد الإلكتروني أو رقم الهاتف مستخدم بالفعل',
                    'errors'  => ['email' => ['البريد الإلكتروني مستخدم بالفعل']],
                ], 409);
            }

            Log::error('[Auth][Register] Database error', [
                'email'    => $request->email,
                'sql_code' => $e->errorInfo[1] ?? null,
                'error'    => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في قاعدة البيانات، يرجى المحاولة مجدداً',
            ], 500);

        } catch (\Throwable $e) {
            Log::error('[Auth][Register] Unexpected error', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع، يرجى المحاولة لاحقًا',
            ], 500);
        }
    }

    /**
     * تسجيل خروج المستخدم
     */
    public function logout(Request $request)
    {
        // حذف التوكن الحالي فقط
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    }

    /**
     * تسجيل خروج من جميع الأجهزة
     */
    public function logoutAll(Request $request)
    {
        // حذف جميع tokens للمستخدم
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج من جميع الأجهزة بنجاح'
        ]);
    }

    /**
     * الحصول على معلومات المستخدم الحالي
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($request->user())
            ]
        ]);
    }

    /**
     * الحصول على الملف الشخصي للمستخدم
     * Profile endpoint for V1 API structure
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($request->user()),
                'profile_completion' => [
                    'percentage' => $this->calculateProfileCompletion($request->user()),
                    'missing_fields' => $this->getMissingProfileFields($request->user())
                ],
                'account_stats' => [
                    'member_since' => $request->user()->created_at->format('Y-m-d'),
                    'last_login' => $request->user()->last_login_at?->format('Y-m-d H:i:s'),
                    'total_calculations' => $request->user()->cost_calculations()->count(),
                    'saved_calculations' => $request->user()->cost_calculations()->where('is_saved', true)->count()
                ]
            ],
            'message' => 'تم جلب بيانات الملف الشخصي بنجاح'
        ]);
    }

    /**
     * حساب نسبة اكتمال الملف الشخصي
     */
    private function calculateProfileCompletion(User $user): int
    {
        $fields = ['name', 'email', 'phone', 'country_id', 'avatar'];
        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    /**
     * الحقول المفقودة في الملف الشخصي
     */
    private function getMissingProfileFields(User $user): array
    {
        $fields = [
            'phone' => 'رقم الهاتف',
            'country_id' => 'البلد',
            'avatar' => 'الصورة الشخصية'
        ];

        $missing = [];
        foreach ($fields as $field => $label) {
            if (empty($user->$field)) {
                $missing[] = [
                    'field' => $field,
                    'label' => $label
                ];
            }
        }

        return $missing;
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'country_id' => 'nullable|exists:countries,id',
            'current_password' => 'nullable|string|min:6',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // التحقق من كلمة المرور الحالية إذا أراد تغييرها
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || 
                !Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلمة المرور الحالية غير صحيحة'
                ], 400);
            }
        }

        // تحديث البيانات
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث البيانات بنجاح',
            'data' => [
                'user' => new UserResource($user->fresh())
            ]
        ]);
    }

    /**
     * نسيان كلمة المرور
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني غير موجود',
                'errors' => $validator->errors()
            ], 422);
        }

        // هنا يمكن إضافة منطق إرسال OTP عبر SMS أو البريد الإلكتروني
        // سأضع placeholder للآن

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز التحقق إلى البريد الإلكتروني'
        ]);
    }

    /**
     * التحقق من رمز OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // هنا يجب التحقق من OTP من قاعدة البيانات أو Redis/Cache
        // سأضع placeholder للآن

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق من الرمز بنجاح',
            'data' => [
                'reset_token' => 'temporary_reset_token_' . time()
            ]
        ]);
    }

    /**
     * إعادة تعيين كلمة المرور
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // هنا يجب التحقق من reset_token
        // سأضع placeholder للآن

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح'
        ]);
    }

    /**
     * تجديد التوكن
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $deviceName = 'mobile-app';
        
        // حذف التوكن الحالي
        $request->user()->currentAccessToken()->delete();
        
        // إنشاء توكن جديد مع صلاحية 60 يوم
        $token = $user->createToken($deviceName, ['*'], now()->addDays(60))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'تم تجديد التوكن بنجاح',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }
}