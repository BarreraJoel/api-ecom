<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleService
{
    public function __construct() {}

    public function getAll(bool $resourceMode = false)
    {
        $roles = Role::all();
        if (!$resourceMode) {
            return $roles;
        }

        $rolesWithResource = [];
        foreach ($roles as $role) {
            array_push($rolesWithResource, $role);
        }

        return $rolesWithResource;
    }

    public function get($id, bool $withResource = false)
    {
        $role = Role::find($id)->first();
        if (!isset($role)) {
            return null;
        }

        return $role;
    }

    public function add(Request $request)
    {
        $role = Role::create($request->all());

        if (!isset($role)) {
            return null;
        }

        return $role;
    }

    public function delete(Role $role)
    {
        return $role->delete();
    }

    public function update(Request $request, Role $role)
    {
        try {
            $role->fill(($request->except('_method')));
            $role->update();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

}
