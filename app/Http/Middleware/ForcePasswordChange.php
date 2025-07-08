<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->force_password_reset) {
            // Allow access only to password change route
            if (! $request->is('change-password') && !$request->is('logout')) {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}
