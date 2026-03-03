<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrackApiUsage
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $this->updateTokenUsage($request);
        } catch (\Throwable $e) {
            // لا نكسر الـ API بسبب تتبع الاستخدام
            // logger()->error('TrackApiUsage failed: '.$e->getMessage());
        }

        return $response;
    }

    private function updateTokenUsage(Request $request): void
    {
        $user = $request->user();
        if (!$user) return;

        $token = method_exists($user, 'currentAccessToken') ? $user->currentAccessToken() : null;
        if (!$token) return;

        // نختار حقل التخزين حسب الموجود فعلاً في جدول personal_access_tokens
        $field = null;
        foreach (['usage', 'meta', 'extra'] as $candidate) {
            if (isset($token->{$candidate})) {
                $field = $candidate;
                break;
            }
        }
        if (!$field) return;

        $raw = $token->{$field};

        // لو كان نص (JSON) هنفكّه
        $storeAsJsonString = false;
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $current = is_array($decoded) ? $decoded : [];
            $storeAsJsonString = true;
        } elseif (is_array($raw)) {
            $current = $raw;
        } else {
            $current = [];
        }

        $path = $request->path();

        // تحديث آمن بدون array_merge على قيمة ممكن تكون string
        $current['total'] = (int)($current['total'] ?? 0) + 1;
        $current['last_path'] = $path;
        $current['last_at'] = now()->toDateTimeString();

        $per = $current['per_endpoint'] ?? [];
        if (is_string($per)) {
            $perDecoded = json_decode($per, true);
            $per = is_array($perDecoded) ? $perDecoded : [];
            $storeAsJsonString = true;
        } elseif (!is_array($per)) {
            $per = [];
        }
        $per[$path] = (int)($per[$path] ?? 0) + 1;
        $tokenValue = $current;
        $tokenValue['per_endpoint'] = $per;

        // لو الحقل أصلاً كان نص، نخزّن كـ JSON string لتجنب TypeError
        $finalValue = $storeAsJsonString ? json_encode($tokenValue, JSON_UNESCAPED_UNICODE) : $tokenValue;

        if (method_exists($token, 'forceFill')) {
            $token->forceFill([$field => $finalValue])->save();
        } else {
            $token->{$field} = $finalValue;
            $token->save();
        }
    }
}
