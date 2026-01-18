<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenController extends Controller
{
    /**
     * الحصول على معلومات الـ tokens النشطة للمستخدم
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $tokens = $user->tokens()->get()->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'created_at' => $token->created_at->toISOString(),
                'last_used_at' => $token->last_used_at?->toISOString(),
                'expires_at' => $token->expires_at?->toISOString(),
                'is_current' => $token->id === $request->user()->currentAccessToken()->id,
                'device_info' => $this->getDeviceInfo($token->name),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'tokens' => $tokens,
                'total_count' => $tokens->count(),
                'current_token_id' => $request->user()->currentAccessToken()->id,
            ]
        ]);
    }

    /**
     * حذف token محدد (تسجيل خروج من جهاز معين)
     */
    public function destroy(Request $request, $tokenId)
    {
        $user = $request->user();
        $currentTokenId = $user->currentAccessToken()->id;
        
        // منع حذف الـ token الحالي
        if ($tokenId == $currentTokenId) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف الجلسة الحالية. استخدم تسجيل الخروج بدلاً من ذلك.'
            ], 400);
        }

        $token = $user->tokens()->find($tokenId);
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'الجلسة غير موجودة'
            ], 404);
        }

        $deviceName = $token->name;
        $token->delete();

        return response()->json([
            'success' => true,
            'message' => "تم تسجيل الخروج من جهاز {$deviceName} بنجاح"
        ]);
    }

    /**
     * حذف جميع الـ tokens ما عدا الحالي
     */
    public function destroyOthers(Request $request)
    {
        $user = $request->user();
        $currentTokenId = $user->currentAccessToken()->id;
        
        $deletedCount = $user->tokens()
            ->where('id', '!=', $currentTokenId)
            ->count();
            
        $user->tokens()
            ->where('id', '!=', $currentTokenId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "تم تسجيل الخروج من {$deletedCount} جهاز آخر بنجاح"
        ]);
    }

    /**
     * التحقق من صحة الـ token الحالي
     */
    public function verify(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        return response()->json([
            'success' => true,
            'data' => [
                'token_valid' => true,
                'token_info' => [
                    'id' => $token->id,
                    'name' => $token->name,
                    'created_at' => $token->created_at->toISOString(),
                    'last_used_at' => $token->last_used_at?->toISOString(),
                    'expires_at' => $token->expires_at?->toISOString(),
                    'days_until_expiry' => $token->expires_at ? 
                        now()->diffInDays($token->expires_at, false) : null,
                ],
                'user' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ]
            ]
        ]);
    }

    /**
     * تجديد الـ token (إنشاء token جديد وحذف القديم)
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $oldToken = $user->currentAccessToken();
        $deviceName = $oldToken->name;

        // إنشاء token جديد
        $newToken = $user->createToken($deviceName, ['*'], now()->addDays(60));

        // حذف الـ token القديم
        $oldToken->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم تجديد الجلسة بنجاح',
            'data' => [
                'token' => $newToken->plainTextToken,
                'token_type' => 'Bearer',
                'expires_in' => 60 * 24 * 60 * 60, // 60 days in seconds
                'expires_at' => now()->addDays(60)->toISOString(),
            ]
        ]);
    }

    /**
     * الحصول على إحصائيات الـ tokens
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        $tokens = $user->tokens();
        $totalTokens = $tokens->count();
        $activeTokens = $tokens->where('last_used_at', '>', now()->subDays(7))->count();
        $expiredTokens = $tokens->where('expires_at', '<', now())->count();

        // إحصائيات الأجهزة
        $deviceStats = $user->tokens()
            ->get()
            ->groupBy('name')
            ->map(function ($tokens, $deviceName) {
                return [
                    'device_name' => $deviceName,
                    'token_count' => $tokens->count(),
                    'last_used' => $tokens->max('last_used_at'),
                    'device_info' => $this->getDeviceInfo($deviceName),
                ];
            })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_tokens' => $totalTokens,
                    'active_tokens' => $activeTokens,
                    'expired_tokens' => $expiredTokens,
                    'devices_count' => $deviceStats->count(),
                ],
                'devices' => $deviceStats,
                'security_tips' => [
                    'إذا كان لديك tokens قديمة غير مستखدمة، احذفها لتحسين الأمان',
                    'راجع قائمة الأجهزة بانتظام وتأكد من أنها جميعاً أجهزتك',
                    'استخدم تسجيل الخروج من جميع الأجهزة إذا فقدت أحد أجهزتك',
                ]
            ]
        ]);
    }

    /**
     * الحصول على معلومات الجهاز من اسم الـ token
     */
    private function getDeviceInfo($tokenName)
    {
        // يمكن تحسين هذا بحفظ معلومات أكثر عن الجهاز
        $deviceInfo = [
            'icon' => '📱',
            'type' => 'mobile',
            'display_name' => 'جهاز محمول',
        ];

        if (str_contains(strtolower($tokenName), 'ios')) {
            $deviceInfo['icon'] = '📱';
            $deviceInfo['type'] = 'ios';
            $deviceInfo['display_name'] = 'iPhone/iPad';
        } elseif (str_contains(strtolower($tokenName), 'android')) {
            $deviceInfo['icon'] = '🤖';
            $deviceInfo['type'] = 'android';
            $deviceInfo['display_name'] = 'Android';
        } elseif (str_contains(strtolower($tokenName), 'web')) {
            $deviceInfo['icon'] = '💻';
            $deviceInfo['type'] = 'web';
            $deviceInfo['display_name'] = 'متصفح الويب';
        }

        return $deviceInfo;
    }
}