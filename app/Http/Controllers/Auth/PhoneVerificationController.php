<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Sms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Phone Verification Controller
 * 
 * TEST CHECKLIST (without Twilio):
 * - افتح الصفحة التي بها فورم إرسال الرمز.
 * - أدخل رقم بصيغة E.164 مثل +201234567890 واضغط إرسال.
 * - افتح storage/logs/laravel.log وابحث عن: [SMS-TEST]
 * - انسخ الرمز الظاهر في الرسالة وأكمل التحقق (verify).
 * - بعد نجاح التحقق يجب ضبط phone_verified_at والتحويل للداشبورد.
 * 
 * PRODUCTION MODE:
 * - ضع القيم في .env: TWILIO_SID, TWILIO_TOKEN, TWILIO_FROM
 * - php artisan config:clear && php artisan optimize:clear
 * - الإرسال يصبح حقيقيًا تلقائيًا (لا تعديل كود إضافي).
 */
class PhoneVerificationController extends Controller
{
    /**
     * Display phone verification page
     */
    public function show()
    {
        if (Auth::user()->phone_verified_at) {
            // Redirect to home for regular users
            return redirect()->route('front.home');
        }

        return view('auth.verify-phone');
    }

    /**
     * Send OTP to phone
     */
    public function send(Request $r, Sms $sms)
    {
        $r->validate(['phone' => 'required']);
        $user = $r->user();
        $user->phone = $r->phone;
        $code = (string)random_int(100000, 999999);
        $user->phone_otp = $code;
        $user->phone_otp_expires_at = now()->addMinutes(10);
        $user->save();

        try {
            $sms->send($user->phone, "رمز التحقق من جماركي: {$code}");
        } catch (\Exception $e) {
            // Fallback to log if SMS fails
            \Log::info("OTP for {$user->phone}: {$code}");
        }

        return back()->with('status', 'sms-sent');
    }

    /**
     * Verify OTP code
     */
    public function verify(Request $r)
    {
        $r->validate(['code' => 'required|string']);
        $u = $r->user();
        abort_if(!$u->phone_otp || now()->gt($u->phone_otp_expires_at), 422, 'انتهت صلاحية الرمز');
        if ($r->code !== $u->phone_otp) abort(422, 'رمز غير صحيح');

        $u->phone_verified_at = now();
        $u->phone_otp = null;
        $u->phone_otp_expires_at = null;
        $u->save();

        return redirect()->intended(route('front.home'))->with('status', 'phone-verified');
    }
}
