<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public static function login($request)
    {
        if (!Auth::attempt($request->all())) {
            return null;
        }

        return Auth::user();
    }

    public static function getCurrentUser() {
        return Auth::user();
    }

    public static function generateToken($user) {
        return $user->createToken('myToken')->plainTextToken;
    } 
    
}
