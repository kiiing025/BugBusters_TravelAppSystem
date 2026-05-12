<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $existingUser = User::where('email', $request->input('email'))->first();

        if ($existingUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already exists',
                'data' => []
            ], 409);
        }

        $user = User::create([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'user',
        ]);

        return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => [
                    'user_id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
        ], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => []
            ], 404);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid password',
                'data' => []
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user_id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email
            ]
        ]);
    }

    public function profile($id)
    {
        $user = User::select('id', 'full_name', 'email', 'role', 'created_at', 'updated_at')
            ->find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile fetched successfully',
            'data' => $user
        ]);
    }
}
