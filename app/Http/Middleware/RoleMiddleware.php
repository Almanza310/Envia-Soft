<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $roles = explode(',', $role);
        
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta área.');
        }

        return $next($request);
    }
}
