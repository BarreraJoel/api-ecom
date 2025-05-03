<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserFilterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(private UserService $userService, private AuthService $authService) {}


    /**
     * Lista todos los usuarios
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'users' => UserService::getAll(true)
        ]);
    }

    /**
     * Muestra un usuario según el id
     * @param \App\Models\User $user Usuario buscado
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => $user ? UserService::toResource($user) : null
        ]);
    }

    /**
     * Actualiza el registro de un usuario
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @param \App\Models\User $user Usuario que se actualizará
     * @throws \Exception Si no se pudo actualizar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        abort_if(
            !Gate::authorize('update_user', [$user, $this->authService->getCurrentUser()]),
            Response::HTTP_FORBIDDEN
        );

        if (!UserService::update($request, $user)) {
            throw new Exception('Hubo un error al actualizar el usuario', Response::HTTP_NOT_MODIFIED);
        }

        return response()->json([
            'message' => 'Usuario actualizado',
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Elimina un usuario según el id
     * @param \App\Models\User $user
     * @throws \Exception Si ocurrió algún error al eliminar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        abort_if(
            !Gate::authorize('delete_user', [$user, $this->authService->getCurrentUser()]),
            Response::HTTP_FORBIDDEN
        );

        if (!UserService::delete($user)) {
            throw new Exception('No se pudo eliminar el usuario', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(
            [],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * Busca y filtra según los parametros dados
     * @param \App\Http\Requests\UserFilterRequest $request
     * @throws \Symfony\Component\HttpFoundation\Exception\BadRequestException Si no se recibió ningún parametro para filtrar
     * @return array Array de usuarios coincidentes
     */
    public function filter(UserFilterRequest $request)
    {
        if (!($request->name || $request->lastname || $request->email || $request->role_id)) {
            throw new BadRequestException('Se requiere de algún campo', Response::HTTP_BAD_REQUEST);
        }
        return $this->userService->filter($request);
    }
}
