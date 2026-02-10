<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Usamos Auth::check() y Auth::user() en lugar de auth()->...
        if (Auth::check() && Auth::user()->email === 'admin@glamur.com') {
            return $next($request);
        }

        abort(403, 'Acceso denegado. Solo administradores.');
    }
}
