<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            $user = $this->findOrCreateUser($socialUser, 'google');
            Auth::login($user, true);
            return $this->toDashboard();
        } catch (\Exception $e) {
            return redirect('/register')->with('error', 'فشل تسجيل الدخول عبر Google.');
        }
    }

    public function redirectToApple()
    {
        return Socialite::driver('apple')->scopes(['name', 'email'])->redirect();
    }

    public function handleAppleCallback()
    {
        try {
            $socialUser = Socialite::driver('apple')->user();
            if (!$socialUser->getEmail()) {
                $socialUser->email = $socialUser->getId() . '@privaterelay.appleid.com';
            }
            $user = $this->findOrCreateUser($socialUser, 'apple');
            Auth::login($user, true);
            return $this->toDashboard();
        } catch (\Exception $e) {
            return redirect('/register')->with('error', 'فشل تسجيل الدخول عبر Apple.');
        }
    }

    protected function findOrCreateUser($socialUser, $provider)
    {
        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();
        if ($user) return $user;

        $user = User::where('email', $socialUser->getEmail())->first();
        if ($user) {
            $user->update(['provider' => $provider, 'provider_id' => $socialUser->getId()]);
            return $user;
        }

        return User::create([
            'name' => $socialUser->getName() ?? 'User',
            'email' => $socialUser->getEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(32)),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ]);
    }

    protected function toDashboard()
    {
        if (Route::has('front.home')) return redirect()->intended(route('front.home'));
        if (Route::has('dashboard')) return redirect()->intended(route('dashboard'));
        return redirect()->intended('/');
    }
}
