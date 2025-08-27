<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        // Obtener el email del request
        $email = $request->email;
        
        // Verificar si el usuario existe
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            // Si el usuario no existe, mostrar vista de error
            return view('auth.account-deleted', [
                'email' => $email,
                'message' => 'No puedes acceder a este sitio porque tu cuenta ha sido eliminada.'
            ]);
        }
        
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Usar el sistema estándar de Laravel para reset de contraseña
        // Laravel automáticamente obtiene el email del token
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            // Generar token único para el mensaje flash
            $messageToken = Str::random(32);

            return redirect()->route('login')
                           ->with('flash_message', 'Tu contraseña ha sido cambiada exitosamente. ¡Inicia sesión con tu nueva contraseña!')
                           ->with('flash_token', $messageToken);
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
