<?php

namespace App\Services;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct() {}

    /**
     * Loguea un usuario 
     * @param mixed $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function login($request)
    {
        if (!Auth::attempt($request->all())) {
            return null;
        }

        return Auth::user();
    }

    /**
     * Registra un usuario
     * @param \App\Http\Requests\Auth\RegisterUserRequest $request
     * @return User
     */
    public function register(RegisterUserRequest $request)
    {
        $except = $this->generateExceptArray($request);
        $user = new User($request->except($except));
        $user->password = Hash::make($request->password);

        if ($request->has('role_id')) {
            $user->role_id = (int)$request->role_id;
        } else {
            $user->role_id = Role::where('name', 'cliente')->first()->id;
        }

        $user->save();

        if ($request->hasFile('image')) {
            $user->updateImage($request->file('image'));
        }

        return $user;
    }

    /**
     * 
     * @param \App\Models\User $user
     * @return bool
     */
    public function logout(User $user)
    {
        $user->tokens()->delete();
        return true;
    }

    /**
     * 
     * @param \App\Http\Requests\Auth\RegisterUserRequest $request
     * @return string[]
     */
    private function generateExceptArray(RegisterUserRequest $request)
    {
        $except = ['password'];
        if ($request->hasFile('image')) {
            array_push($except, 'image');
        }

        if ($request->has('role_id')) {
            array_push($except, 'role_id');
        }

        return $except;
    }

    /**
     * 
     * @param bool $authenticable
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Collection<int, User>|null
     */
    public function getCurrentUser(bool $authenticable = false)
    {
        $userAuth = Auth::user();
        return $authenticable ? $userAuth : User::find($userAuth->getAuthIdentifier());
    }

    /**
     * 
     * @param mixed $user
     */
    public function generateToken($user)
    {
        return $user->createToken('myToken')->plainTextToken;
    }
}
