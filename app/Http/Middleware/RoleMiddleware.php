<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, $role): Response
    // {
    //     if (Auth::check() && Auth::user()->role == $role) {
    //         return $next($request);
    //     }
    //     Log::error('Unauthorized: Expected role ' . $role . ', Got ' . (Auth::check() ? Auth::user()->role : 'Not authenticated'));
    //     abort(403, 'Unauthorized');
    // }

    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on role instead of 403
        switch ($user->role) {
            case 'admin':
                return redirect('/dashboard/admin');
            case 'researcher':
                return redirect('/researcher');
            case 'reviewer':
                return redirect('/reviewer');
            case 'institution':
                return redirect('/institution');
            case 'department':
                return redirect('/department');
            default:
                return redirect('/login');
        }
    }
}
