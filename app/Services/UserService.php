<?php

namespace App\Services;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    public function __construct() {}

    public static function getAll(bool $resourceMode = false)
    {
        $users = User::all();
        if (!$resourceMode) {
            return $users;
        }

        $usersWithResource = [];
        foreach ($users as $user) {
            array_push($usersWithResource, UserService::toResource($user));
        }

        return $usersWithResource;
    }

    public static function toResource(User $user)
    {
        return new UserResource($user);
    }

    public static function get($id, bool $withResource = false)
    {
        $user = User::find($id)->first();
        if (!isset($user)) {
            return null;
        }

        return $withResource ? UserService::toResource($user) : $user;
    }

    public static function delete(User $user)
    {
        if ($user->image_url) {
            $fileService = new FileService();
            $fileService->removeImage($user->image_url);
        }

        $user->products()->detach();
        $user->tokens()->delete();

        return $user->delete();
    }

    public static function update(UpdateUserRequest $request, User $User)
    {
        try {
            $except = ['_method'];

            if ($request->hasFile('image')) {
                array_push($except, 'image');
                $fileService = new FileService();
                if ($User->image_url) {
                    $fileService->removeImage($User->image_url);
                }
                $filename = $fileService->generateFileName($User->id);
                $path = $fileService->upload($request->file('image'), 'users/images', $filename);
                $User->image_url = $path;
            }

            $User->fill(($request->except($except)));
            $User->update();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function filter(Request $request)
    {
        $filterService = new FilterService();
        $users = $filterService->filterUser($request);

        $usersWithResource = [];
        foreach ($users as $user) {
            array_push($usersWithResource, UserService::toResource($user));
        }

        return $usersWithResource;
    }

}
