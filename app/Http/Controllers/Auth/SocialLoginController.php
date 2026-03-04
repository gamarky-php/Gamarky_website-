<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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

    public function mobileGoogleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'provider' => 'required|string|in:google',
            'provider_id' => 'required|string|max:255',
            'avatar' => 'nullable|string|max:2048',
            'id_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::where('provider', 'google')
                ->where('provider_id', $request->provider_id)
                ->first();

            if (!$user) {
                $user = User::where('email', $request->email)->first();
            }

            if ($user) {
                $user->name = $request->filled('name') ? $request->name : ($user->name ?: 'User');
                $user->provider = 'google';
                $user->provider_id = $request->provider_id;
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                }
                $user->save();
            } else {
                $user = User::create([
                    'name' => $request->input('name', 'User'),
                    'email' => $request->email,
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(32)),
                    'provider' => 'google',
                    'provider_id' => $request->provider_id,
                ]);
            }

            $token = $user->createToken('mobile')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Google login successful',
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Google mobile login failed', [
                'email' => $request->email,
                'provider_id' => $request->provider_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save Google user',
            ], 500);
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
