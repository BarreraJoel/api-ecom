<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartIsNotEmptyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authService = new AuthService();
        $user = $authService->getCurrentUser();
        
        if(count($user->products) > 0) {
            return $next($request);
        }

        return response()->json([
            'message' => 'El carrito está vacío. No se puede procesar el checkout'
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
        
    }
}
