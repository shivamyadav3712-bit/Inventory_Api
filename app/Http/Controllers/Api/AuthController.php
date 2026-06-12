<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([

            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'

        ]);

        $user = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password)

        ]);

        return response()->json([

            'message' => 'User Registered Successfully',
            'user' => $user

        ]);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {

            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        return response()->json([

            'message' => 'Login Successful',
            'token' => $token,
            'user' => auth('api')->user()

        ]);
    }

    // Profile
    public function profile()
    {
        return response()->json(auth('api')->user());
    }
}