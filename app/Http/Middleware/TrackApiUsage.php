<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TrackApiUsage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // تسجيل استخدام الـ API إذا كان المستخدم مُسجل دخول
        if ($request->user() && $request->bearerToken()) {
            $this->updateTokenUsage($request);
        }

        return $response;
    }

    /**
     * تحديث معلومات استخدام الـ token
     */
    private function updateTokenUsage(Request $request)
    {
        try {
            $token = $request->user()->currentAccessToken();
            
            if ($token) {
                // تحديث وقت آخر استخدام
                $token->last_used_at = now();
                
                // حفظ معلومات إضافية عن الطلب
                $tokenData = $token->token_data ?? [];
                $tokenData = array_merge($tokenData, [
                    'last_ip' => $request->ip(),
                    'last_user_agent' => $request->userAgent(),
                    'last_endpoint' => $request->path(),
                    'usage_count' => ($tokenData['usage_count'] ?? 0) + 1,
                    'updated_at' => now()->toISOString(),
                ]);
                
                // حفظ معلومات الموقع الجغرافي (اختياري)
                if (!isset($tokenData['first_location'])) {
                    $tokenData['first_location'] = $this->getLocationFromIP($request->ip());
                }
                
                $token->token_data = $tokenData;
                $token->saveQuietly(); // لا نريد إثارة events
            }
        } catch (\Exception $e) {
            // تجاهل الأخطاء لعدم تأثيرها على الـ API
            logger('Error tracking API usage: ' . $e->getMessage());
        }
    }

    /**
     * الحصول على معلومات الموقع من الـ IP (اختياري)
     */
    private function getLocationFromIP($ip)
    {
        // يمكن استخدام خدمة خارجية مثل GeoIP
        // للآن سنحفظ معلومات أساسية فقط
        
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return [
                'country' => 'Local',
                'city' => 'localhost',
                'ip' => $ip
            ];
        }

        return [
            'ip' => $ip,
            'timestamp' => now()->toISOString()
        ];
    }
}