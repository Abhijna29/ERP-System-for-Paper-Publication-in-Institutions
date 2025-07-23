<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Mail\NewUserCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InstitutionDepartmentController extends Controller
{
    public function index()
    {
        return view('dashboard.institution.department');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|digits:10|unique:users,mobile_number',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:department',
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
            'institution_id' => Auth::user()->id,
        ]);
        Mail::to($user->email)->send(new NewUserCreated($user, $password = $request->password));

        return response()->json(['message' => 'Department created successfully', 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)
            ->where('role', 'department')
            ->where('institution_id', Auth::user()->id)
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
            'role' => 'department',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['message' => 'Department updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)
            ->where('role', 'department')
            ->where('institution_id', Auth::user()->id) // ✅ Restrict access
            ->firstOrFail();
        $user->delete();

        return response()->json(['message' => 'Department deleted successfully']);
    }

    public function getDepartments()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->role !== 'institution') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $users = User::where('role', 'department')
            ->where('institution_id', $user->id)
            ->select('id', 'name', 'email', 'mobile_number')
            ->get();

        return response()->json($users);
    }
}
