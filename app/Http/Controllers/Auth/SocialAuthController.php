<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@'),
                    'password' => bcrypt(Str::random(32)),
                    'email_verified_at' => now(),
                ]
            );
            Auth::login($user, true);
            return redirect()->intended(route('front.home'));
        } catch (Exception $e) {
            report($e);
            return redirect()->route('login')->with('error', 'تعذّر إكمال تسجيل الدخول عبر Google. تحقق من الإعدادات وحاول مرة أخرى.');
        }
    }
}
