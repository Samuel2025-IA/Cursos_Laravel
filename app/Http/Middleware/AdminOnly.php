<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'No autenticado');
        }
        
        if ($user->rol !== 'admin') {
            abort(403, 'Acceso denegado. Solo los administradores pueden acceder a esta sección.');
        }
        
        return $next($request);
    }
}















