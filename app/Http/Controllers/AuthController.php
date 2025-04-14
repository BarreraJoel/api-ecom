<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
    // private AuthService $authService;
    public function __construct(private AuthService $authService)
    {
        // $this->authService = new AuthService();
    }

    /**
     * Maneja el flujo para registrar un usuario
     * @param \App\Http\Requests\Auth\RegisterUserRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse Respuesta
     */
    public function register(RegisterUserRequest $request)
    {
        try {

            if (!ValidationService::validate($request)) {
                throw new BadRequestException('Credenciales invalidas', Response::HTTP_BAD_REQUEST);
            }

            if (!$this->authService->register($request)) {
                throw new Exception('Hubo un error al registrar el usuario', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            return response()->json([
                'message' => 'Usuario registrado'
            ], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }

    /**
     * Maneja el flujo de para iniciar sesión
     * @param \App\Http\Requests\Auth\LoginUserRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        try {
            if (!ValidationService::validate($request)) {
                throw new BadRequestException('Credenciales invalidas', Response::HTTP_BAD_REQUEST);
            }

            $user = AuthService::login($request);
            if (!isset($user)) {
                throw new NotFoundHttpException('Usuario no encontrado', null, Response::HTTP_NOT_FOUND);
            }

            $token = AuthService::generateToken($user);

            return response()->json([
                'message' => 'Se logueo el usuario',
                'token' => $token
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    /**
     * Maneja el flujo para cerrar sesión
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            if (!$request->user()) {
                throw new Exception('Debe loguearse primero', Response::HTTP_NOT_FOUND);
            }

            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Se deslogueo el usuario'
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    /**
     * Maneja el flujo para obtener el usuario logueado
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getCurrentUser(Request $request)
    {
        try {
            if (!$request->user()) {
                throw new Exception('Debe loguearse primero', Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([
                'user' => new UserResource($request->user())
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }
}
