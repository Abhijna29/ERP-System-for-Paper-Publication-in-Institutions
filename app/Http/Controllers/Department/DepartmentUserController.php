<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\NewUserCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DepartmentUserController extends Controller
{
    public function index()
    {
        $departmentId = Auth::user()->id; // Department is the logged-in user

        // Fetch users where department_id matches the department's ID
        $users = User::whereIn('role', ['researcher', 'reviewer'])
            ->where('department_id', $departmentId)
            ->get();

        return view('dashboard.department.createUsers', compact('users'));
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
            'department_id' => Auth::id(),
            'institution_id' => Auth::user()->institution_id,
        ]);
        Mail::to($user->email)->send(new NewUserCreated($user, $password = $request->password));

        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)
            ->whereIn('role', ['researcher', 'reviewer'])
            ->where('department_id', Auth::user()->id) // ✅ Verify ownership
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile_number' => 'required|digits:10|unique:users,mobile_number,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
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
            ->where('department_id', Auth::user()->id)->firstOrFail();

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function getUsers()
    {
        $departmentId = Auth::user()->id; // Department is the logged-in user

        // Fetch users where department_id matches the department's ID
        $users = User::whereIn('role', ['researcher', 'reviewer'])
            ->where('department_id', $departmentId)
            ->get();

        // Format department name
        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile_number' => $user->mobile_number,
                'role' => $user->role,
            ];
        });

        return response()->json($formattedUsers);
    }
}
