<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Hash;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = new User($request->except('password'));
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User registered'
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        if (!ValidationService::validate($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ]);
        }

        $user = AuthService::login($request);

        if (!isset($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        $token = AuthService::generateToken($user);

        return response()->json([
            'success' => true,
            'message' => 'User logged in',
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in'
            ]);
        }

        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out'
        ]);
    }

    public function getCurrentUser(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in'
            ]);
        }

        return response()->json([
            'user' => $request->user() ? new UserResource($request->user()) : null
        ]);
    }
}
