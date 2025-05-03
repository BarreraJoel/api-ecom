<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!($request->user()->role_id == Role::where('name', 'administrador')->first()->id)) {
            throw new AuthorizationException();
        }
        
        return $next($request);
    }
}
