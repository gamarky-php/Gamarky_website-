<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        // لا تعبث بالملفات/الستريمات
        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        // رؤوس أساسية آمنة
        $this->setHeaderIfMissing($response, 'X-Frame-Options', 'SAMEORIGIN');
        $this->setHeaderIfMissing($response, 'X-Content-Type-Options', 'nosniff');
        $this->setHeaderIfMissing($response, 'Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->setHeaderIfMissing($response, 'X-XSS-Protection', '1; mode=block');
        $response->headers->remove('X-Powered-By'); // إخفاء البصمة

        // HSTS للإنتاج وعلى HTTPS فقط
        if (app()->environment('production') && $request->isSecure()) {
            $this->setHeaderIfMissing(
                $response,
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // CSP: مرنة في التطوير، مشدّدة في الإنتاج
        if (! $response->headers->has('Content-Security-Policy')) {
            $csp = app()->environment('production')
                // إنتاج: صارم (بدون inline/eval). عدّل المصادر حسب احتياجك.
                ? "default-src 'self'; script-src 'self' https:; style-src 'self' https:; img-src 'self' data: blob: https:; font-src 'self' https: data:; connect-src 'self' https:; object-src 'none'; base-uri 'self'; frame-ancestors 'self';"
                // تطوير/ستيجنغ: يسمح لـ Vite و inline/eval لتجاربك
                : "default-src 'self' http: https: data: blob:; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173 https://localhost:5173; style-src 'self' 'unsafe-inline' http://localhost:5173 https://localhost:5173; img-src 'self' data: blob: http: https:; font-src 'self' data: http: https:; connect-src 'self' ws://localhost:5173 http://localhost:5173 https://localhost:5173 http: https:; object-src 'none'; base-uri 'self'; frame-ancestors 'self';";

            $response->headers->set('Content-Security-Policy', $csp);
        }

        // COOP/COEP (اختياري): فعلها فقط عند الحاجة لتفعيل isolation
        // $this->setHeaderIfMissing($response, 'Cross-Origin-Opener-Policy', 'same-origin');
        // $this->setHeaderIfMissing($response, 'Cross-Origin-Embedder-Policy', 'require-corp');

        return $response;
    }

    private function setHeaderIfMissing(Response $response, string $key, string $value): void
    {
        if (! $response->headers->has($key)) {
            $response->headers->set($key, $value);
        }
    }
}
