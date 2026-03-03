<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialLogonController extends Controller
{
    // Google OAuth
    public function redirect()
    {
        // Debug: Log the exact redirect URI being used
        \Log::info('Google OAuth Redirect URI: ' . config('services.google.redirect'));
        
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        // Check if user cancelled or error occurred
        if (request()->has('error')) {
            \Log::warning('Google OAuth cancelled or error: ' . request('error'));
            return redirect()->route('register')->with('error', __('messages.google_login_cancelled'));
        }
        
        // Check if code parameter exists
        if (!request()->has('code')) {
            \Log::error('Google OAuth callback missing code parameter');
            return redirect()->route('register')->with('error', __('messages.google_login_error'));
        }

        try {
            $g = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $g->getEmail()],
                [
                    'name' => $g->getName() ?: 'User',
                    'password' => bcrypt(Str::random(32)),
                    // لو عندك عمود email_verified_at:
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user, true);
            return redirect()->intended('/'); // عدّل الوجهة إذا لزم
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('register')->with('error', __('messages.google_login_error_general'));
        }
    }

    // Apple OAuth (safe stubs - won't break if not configured)
    public function appleRedirect()
    {
        if (!config('services.apple.enabled')) {
            abort(404);
        }
        
        // TODO: replace with Socialite::driver('apple')->redirect();
        return redirect()->route('register')->with('status', 'Apple Sign-In is disabled until configured.');
    }

    public function appleCallback(Request $request)
    {
        if (!config('services.apple.enabled')) {
            abort(404);
        }
        
        // TODO: handle Socialite callback here safely
        return redirect()->route('dashboard');
    }
}
