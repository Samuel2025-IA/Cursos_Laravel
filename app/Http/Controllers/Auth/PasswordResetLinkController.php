<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        Log::info('Accediendo a la vista de recuperación de contraseña');
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        Log::info('Iniciando proceso de recuperación de contraseña', [
            'email' => $request->email,
            'request_data' => $request->all()
        ]);

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        Log::info('Validación exitosa, enviando enlace de recuperación');

        // Usar el sistema estándar de Laravel para enviar el enlace de recuperación
        $status = Password::sendResetLink(
            $request->only('email')
        );

        Log::info('Resultado del envío de enlace', [
            'status' => $status,
            'email' => $request->email
        ]);

        if ($status == Password::RESET_LINK_SENT) {
            Log::info('Enlace de recuperación enviado exitosamente');
            return redirect()->route('password.request')
                        ->with('status', __($status));
        }

        Log::error('Error al enviar enlace de recuperación', [
            'status' => $status,
            'email' => $request->email
        ]);

        return back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }
}
