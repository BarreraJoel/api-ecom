<?php

namespace App\Services;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    /**
     * Loguea un usuario 
     * @param mixed $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function login($request)
    {
        if (!Auth::attempt($request->all())) {
            return null;
        }

        return Auth::user();
    }

    /**
     * Registra un usuario
     * @param mixed $request
     * @return bool
     */
    public function register(RegisterUserRequest $request)
    {
        $user = new User($request->except('password'));
        $user->password = Hash::make($request->password);
        $registered = $user->save();

        if ($request->hasFile('image')) {
            $registered = $user->updateImage($request->file('image'));
        }

        return $registered;
    }

    public static function getCurrentUser()
    {
        return Auth::user();
    }

    public static function generateToken($user)
    {
        return $user->createToken('myToken')->plainTextToken;
    }
}
