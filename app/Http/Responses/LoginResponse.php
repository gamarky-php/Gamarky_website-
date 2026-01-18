<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return $request->user()->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->intended('/');
    }
}
