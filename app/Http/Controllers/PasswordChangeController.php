<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.forcePasswordChange');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = \App\Models\User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->force_password_reset = false;
        $user->save();

        switch ($user->role) {
            case 'researcher':
                return redirect()->route('researcher.dashboard');
            case 'reviewer':
                return redirect()->route('reviewer.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'institution':
                return redirect()->route('institute.dashboard');
            default:
                return redirect('/'); // fallback
        }
    }
}
