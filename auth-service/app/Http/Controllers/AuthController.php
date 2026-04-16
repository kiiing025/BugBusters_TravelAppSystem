<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $existingUser = DB::table('users')
            ->where('email', $request->input('email'))
            ->first();

        if ($existingUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already exists',
                'data' => []
            ], 409);
        }

        $userId = DB::table('users')->insertGetId([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user_id' => $userId
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = DB::table('users')
            ->where('email', $request->input('email'))
            ->first();

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
        $user = DB::table('users')
            ->select('id', 'full_name', 'email', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

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