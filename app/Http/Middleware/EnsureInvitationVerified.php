<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\InvitationCode;
use Illuminate\Support\Facades\Auth;

class EnsureInvitationVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log para debug - solo en desarrollo
        if (config('app.debug')) {
            \Log::info('EnsureInvitationVerified middleware ejecutándose', [
                'url' => $request->url(),
                'method' => $request->method(),
                'is_authenticated' => Auth::check(),
                'invitation_email' => session('invitation_email'),
                'invitation_code_id' => session('invitation_code_id')
            ]);
        }

        // Si el usuario ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            if (config('app.debug')) {
                \Log::info('Usuario autenticado, redirigiendo a dashboard');
            }
            return redirect()->route('dashboard');
        }
        
        // Verificar si hay un código de invitación válido en la sesión
        if (!session('invitation_email') || !session('invitation_code_id')) {
            if (config('app.debug')) {
                \Log::info('No hay código de invitación en sesión, redirigiendo a verify');
            }
            return redirect()->route('invitation.verify')
                ->with('error', 'Debes verificar un código de invitación válido antes de poder registrarte.')
                ->with('info', 'Para registrarte en la plataforma, necesitas un código de invitación válido.');
        }

        // Verificar si tiene status de verificado
        if (session('invitation_status') !== 'verified') {
            if (config('app.debug')) {
                \Log::info('No tiene status de verificado, redirigiendo a verify');
            }
            return redirect()->route('invitation.verify')
                ->with('error', 'Debes verificar tu código de invitación antes de continuar.')
                ->with('info', 'Para acceder al registro, necesitas verificar tu código de invitación.');
        }

        // Verificar que el código sigue siendo válido y no ha expirado
        $invitationCode = InvitationCode::find(session('invitation_code_id'));
        if (!$invitationCode || !$invitationCode->isValid()) {
            if (config('app.debug')) {
                \Log::info('Código de invitación inválido o expirado', [
                    'code_exists' => $invitationCode ? 'yes' : 'no',
                    'is_valid' => $invitationCode ? $invitationCode->isValid() : 'N/A'
                ]);
            }
            
            // Limpiar la sesión si el código no es válido
            session()->forget(['invitation_email', 'invitation_code_id']);
            
            return redirect()->route('invitation.verify')
                ->with('error', 'El código de invitación ha expirado, ya fue usado o no es válido.')
                ->with('info', 'Por favor, solicita un nuevo código de invitación o verifica que el código sea correcto.');
        }

        // Si todo está bien, continuar con la solicitud
        if (config('app.debug')) {
            \Log::info('Código de invitación válido, permitiendo acceso al registro');
        }
        return $next($request);
    }
}
