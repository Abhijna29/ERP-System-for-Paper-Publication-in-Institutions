<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\NewUserCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.admin.createInstitution');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|digits:10|unique:users,mobile_number',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:institution',
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
        ]);
        Mail::to($user->email)->send(new NewUserCreated($user, $password = $request->password));
        return response()->json(['message' => 'Institution created successfully', 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile_number' => 'required|digits:10|unique:users,mobile_number,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:institution'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'role' => 'institution',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['message' => 'Institution updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Institution deleted successfully']);
    }

    public function getInstitutions()
    {
        $users = User::where('role', 'institution')
            ->select('id', 'name', 'email', 'mobile_number')
            ->get();
        return response()->json($users);
    }
}
