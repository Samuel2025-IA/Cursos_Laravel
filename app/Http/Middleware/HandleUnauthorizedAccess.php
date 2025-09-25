<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleUnauthorizedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Debes iniciar sesión primero para acceder a esta página.'
                ], 401);
            }
            
            // Para peticiones normales, mostrar la vista de login con alerta
            return redirect()->route('login')
                ->with('unauthorized_error', 'Debes iniciar sesión primero para acceder a esta página.');
        }

        return $next($request);
    }
}
