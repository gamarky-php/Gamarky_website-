<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * الحصول على معلومات المستخدم
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load(['country', 'roles', 'subscription']);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user)
            ]
        ]);
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'country_id' => 'nullable|exists:countries,id',
            'preferred_language' => 'nullable|string|in:ar,en',
            'theme_preference' => 'nullable|string|in:light,dark,auto',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث البيانات بنجاح',
            'data' => [
                'user' => new UserResource($user->fresh()->load(['country', 'roles']))
            ]
        ]);
    }

    /**
     * رفع صورة المستخدم
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ملف الصورة غير صالح',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        try {
            // حذف الصورة القديمة إن وجدت
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // رفع الصورة الجديدة
            $file = $request->file('avatar');
            $filename = 'avatars/' . $user->id . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('', $filename, 'public');

            // تحديث المستخدم
            $user->update(['avatar' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الصورة بنجاح',
                'data' => [
                    'avatar_url' => Storage::disk('public')->url($path)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الصورة'
            ], 500);
        }
    }

    /**
     * الحصول على الإشعارات
     */
    public function notifications(Request $request)
    {
        $user = $request->user();
        
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                    'has_more' => $notifications->hasMorePages(),
                ]
            ]
        ]);
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markNotificationRead(Request $request, $id)
    {
        $user = $request->user();
        
        $notification = $user->notifications()->find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'الإشعار غير موجود'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد الإشعار كمقروء'
        ]);
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllNotificationsRead(Request $request)
    {
        $user = $request->user();
        
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد جميع الإشعارات كمقروءة'
        ]);
    }

    /**
     * الحصول على إحصائيات المستخدم
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'calculations' => [
                'total' => $user->costCalculations()->count(),
                'this_month' => $user->costCalculations()->whereMonth('created_at', now()->month)->count(),
                'saved' => $user->costCalculations()->whereNotNull('saved_at')->count(),
            ],
            'articles' => [
                'read' => $user->articleViews()->count(),
                'bookmarked' => $user->bookmarkedArticles()->count(),
            ],
            'activity' => [
                'last_login' => $user->last_seen_at?->toISOString(),
                'member_since' => $user->created_at?->toISOString(),
                'profile_completion' => $this->calculateProfileCompletion($user),
            ],
            'notifications' => [
                'unread_count' => $user->unreadNotifications()->count(),
                'total_count' => $user->notifications()->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats
            ]
        ]);
    }

    /**
     * حساب نسبة اكتمال البروفايل
     */
    private function calculateProfileCompletion($user)
    {
        $fields = [
            'name' => !empty($user->name),
            'email' => !empty($user->email),
            'phone' => !empty($user->phone),
            'country_id' => !empty($user->country_id),
            'avatar' => !empty($user->avatar),
            'email_verified' => !is_null($user->email_verified_at),
        ];

        $completed = array_sum($fields);
        $total = count($fields);

        return [
            'percentage' => round(($completed / $total) * 100),
            'completed_fields' => $completed,
            'total_fields' => $total,
            'missing_fields' => array_keys(array_filter($fields, fn($value) => !$value))
        ];
    }
}