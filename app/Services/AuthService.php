<?php

namespace App\Services;

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
    public function register($request)
    {
        $fileService = new FileService();
        $user = new User($request->except('password'));
        $user->password = Hash::make($request->password);
        if ($request->has('image_url')) {
            $user->save();
            $filename = $fileService->generateFileName($user->id);
            $path = $fileService->upload($request->file('image_url'), '/users/images', $filename);
            $user->image_url = $path;
            return $user->save();
        }
        return $user->save();
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
