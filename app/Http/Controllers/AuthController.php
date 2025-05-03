<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * 
     * @param \App\Services\AuthService $authService Inyección del servicio de autenticación
     */
    public function __construct(private AuthService $authService) {}

    /**
     * Maneja el flujo para registrar un usuario
     * @param \App\Http\Requests\Auth\RegisterUserRequest $request
     * @throws \Exception Si falla el registro del usuario
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $newUser = $this->authService->register($request);
        if (!isset($newUser)) {
            throw new Exception('Hubo un error al registrar el usuario', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::response(
            true,
            'Usuario registrado',
            [
                'user' => $newUser->toResource()
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Maneja el flujo para loguear a un usuario
     * @param \App\Http\Requests\Auth\LoginUserRequest $request
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se pudo encontrar el usuario
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        $user = $this->authService->login($request);
        if (!isset($user)) {
            throw new ModelNotFoundException('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }

        $token = $this->authService->generateToken($user);

        return ApiResponse::response(
            true,
            'Usuario logueado',
            [
                'token' => $token
            ]
        );
    }

    /**
     * Maneja el flujo para cerrar sesión
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        if (!$this->authService->logout($request->user())) {
            throw new Exception('No se pudo cerrar la sesión');
        }

        return ApiResponse::response(
            true,
            'Usuario deslogueado',
            null
        );
    }

    /**
     * Maneja el flujo para obtener el usuario logueado
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getCurrentUser(Request $request)
    {
        return ApiResponse::response(
            true,
            null,
            [
                'user' => new UserResource($this->authService->getCurrentUser())
            ]
        );
    }
}
