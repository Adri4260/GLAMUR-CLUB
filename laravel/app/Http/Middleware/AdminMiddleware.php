<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si está logueado
        if (! $request->user()) {
            // Si es una petición API, devolvemos JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'No autorizado'], 401);
            }
            return redirect('/login');
        }

        // 2. Verificamos si tiene el rol 'admin' en la base de datos
        if (! $request->user()->hasRole('admin')) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Acceso denegado. Se requiere rol de administrador.'], 403);
            }
            // En web normal, daría error 403
            abort(403, 'Acceso denegado');
        }

        return $next($request);
    }
}
