<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'country' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'activity_type' => ['required', 'in:import,export,manufacturing,broker,containers,agent'],
            'business_sector' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'country' => $validated['country'],
            'phone' => $validated['phone'],
            'activity_type' => $validated['activity_type'],
            'business_sector' => $validated['business_sector'],
            'email_verified_at' => now(), // Auto-verify for local development
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended('/');
    }
}