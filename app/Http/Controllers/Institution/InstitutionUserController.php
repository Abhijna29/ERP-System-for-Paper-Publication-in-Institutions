<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Mail\NewUserCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InstitutionUserController extends Controller
{
    public function index()
    {
        $institutionId = Auth::user()->id; // assuming institution is logged in user

        // Fetch users with role 'department' belonging to this institution
        $departments = User::where('role', 'department')
            ->where('institution_id', $institutionId)
            ->get();

        return view('dashboard.institution.createUsers', compact('departments'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|digits:10|unique:users,mobile_number',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:researcher,reviewer',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'force_password_reset' => true,
            'institution_id' => Auth::user()->id,
            'department_id' => $request->department_id,
        ]);
        Mail::to($user->email)->send(new NewUserCreated($user, $password = $request->password));

        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)
            ->whereIn('role', ['researcher', 'reviewer'])
            ->where('institution_id', Auth::user()->id) // ✅ Verify ownership
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile_number' => 'required|digits:10|unique:users,mobile_number,' . $id,
            'password' => 'nullable|string|min:6',
            'department_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'department_id' => $request->department_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)
            ->whereIn('role', ['researcher', 'reviewer'])
            ->where('institution_id', Auth::user()->id) // ✅ Ensure it's your user
            ->firstOrFail();

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function getUsers()
    {
        $institutionId = Auth::id();

        // Fetch only users created by this institution (excluding departments)
        $users = User::with('department')->where('institution_id', $institutionId)->whereIn('role', ['researcher', 'reviewer'])
            ->get();

        // Format department name
        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile_number' => $user->mobile_number,
                'role' => $user->role,
                'department_name' => $user->department?->name
            ];
        });

        return response()->json($formattedUsers);
    }
}
