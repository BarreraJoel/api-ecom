<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\RoleService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{

    public function __construct(private RoleService $roleService) {}

    /**
     * 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'roles' => $this->roleService->getAll(true)
        ]);
    }

    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $newRole = $this->roleService->add($request);
        if (!$newRole) {
            throw new Exception('Hubo un error al agregar el rol', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Rol agregado',
            'role' => $newRole
        ], Response::HTTP_CREATED);
    }

    /**
     * 
     * @param \App\Models\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return response()->json([
            'role' => $role
        ]);
    }

    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Role $role
     * @return void
     */
    public function update(Request $request, Role $role)
    {
        if(!$this->roleService->update($request, $role)) {
            throw new Exception('Hubo un error al actualizar el rol', Response::HTTP_NOT_MODIFIED);
        }

        return response()->json([
            'message' => 'Rol actualizado',
            'role' => $role
        ]);
    }

    /**
     * 
     * @param \App\Models\Role $role
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        if(!$this->roleService->delete($role)){
            throw new Exception('No se pudo eliminar el rol', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Rol eliminado'
        ], Response::HTTP_NO_CONTENT);
    }
}
