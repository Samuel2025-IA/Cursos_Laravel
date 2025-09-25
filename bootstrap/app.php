<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.custom' => \App\Http\Middleware\HandleUnauthorizedAccess::class,
            'invitation.verified' => \App\Http\Middleware\EnsureInvitationVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            // Capturar errores de conexión a la base de datos
            if ($e instanceof \Illuminate\Database\QueryException) {
                $message = $e->getMessage();
                if (str_contains($message, 'Connection refused') || 
                    str_contains($message, 'denegó expresamente') ||
                    str_contains($message, 'No se puede establecer una conexión') ||
                    str_contains($message, 'SQLSTATE[HY000] [2002]')) {
                    return response()->view('errors.500', [], 500);
                }
            }
            
            // Capturar otros errores de base de datos
            if ($e instanceof \PDOException) {
                $message = $e->getMessage();
                if (str_contains($message, 'Connection refused') || 
                    str_contains($message, 'denegó expresamente') ||
                    str_contains($message, 'No se puede establecer una conexión') ||
                    str_contains($message, 'SQLSTATE[HY000] [2002]')) {
                    return response()->view('errors.500', [], 500);
                }
            }
            
            return null;
        });
    })->create();
