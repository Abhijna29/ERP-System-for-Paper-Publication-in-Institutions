<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected function redirectTo()
    {
        $role = Auth::user()->role;
        switch ($role) {
            case 'researcher':
                return '/researcher';
            case 'reviewer':
                return '/reviewer';
            case 'admin':
                return '/dashboard/admin';
            case 'institution':
                return '/institution';
            case 'department':
                return '/department';
            default:
                return '/login';
        }
    }

    // protected function resetPassword(Authenticatable $user, $password)
    // {
    //     Log::info('Resetting password for user: ' . $user->email);
    //     $user->password = Hash::make($password);
    //     $user->setRememberToken(Str::random(60));
    //     $user->save();
    //     Log::info('Password reset successful, logging in user: ' . $user->email);
    //     $this->guard()->login($user);
    // }

    // public function reset(Request $request)
    // {
    //     Log::info('Password reset attempt for email: ' . $request->email);
    //     $response = parent::reset($request);
    //     Log::info('Password reset response: ' . $response);
    //     if ($response == Password::PASSWORD_RESET) {
    //         Log::info('Password reset successful, redirecting to: ' . $this->redirectTo);
    //         return redirect()->route('home');
    //     }
    //     return $response;
    // }
}
