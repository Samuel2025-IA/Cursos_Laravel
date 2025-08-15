<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class PasswordResetCodeController extends Controller
{
    /**
     * Muestra la vista para solicitar el código de recuperación
     */
    public function showRequestForm(): View
    {
        return view('auth.forgot-password-code');
    }

    /**
     * Envía el código de recuperación por correo
     */
    public function sendCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $request->email;

        // Limpia códigos expirados
        PasswordResetCode::cleanExpiredCodes();

        // Genera un nuevo código
        $code = PasswordResetCode::generateCode();
        $expiresAt = Carbon::now()->addMinutes(15); // Expira en 15 minutos

        // Guarda el código en la base de datos
        PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        // Envía el correo con el código
        $this->sendCodeEmail($email, $code);

        return back()->with('status', 'Se ha enviado un código de verificación a tu correo electrónico.');
    }

    /**
     * Muestra la vista para ingresar el código
     */
    public function showCodeForm(Request $request): View
    {
        return view('auth.enter-code', ['email' => $request->email]);
    }

    /**
     * Verifica el código y permite cambiar la contraseña
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:8'],
        ]);

        $email = $request->email;
        $code = $request->code;

        // Busca el código válido
        $resetCode = PasswordResetCode::findValidCode($email, $code);

        if (!$resetCode) {
            return back()->withErrors(['code' => 'El código es inválido o ha expirado.']);
        }

        // Marca el código como usado
        $resetCode->markAsUsed();

        // Genera un token temporal para cambiar la contraseña
        $token = Str::random(60);
        
        // Guarda el token en la sesión
        session(['password_reset_token' => $token, 'password_reset_email' => $email]);

        return redirect()->route('password.reset.with-code')->with('status', 'Código verificado correctamente. Ahora puedes cambiar tu contraseña.');
    }

    /**
     * Muestra la vista para cambiar la contraseña
     */
    public function showResetForm(): View
    {
        if (!session('password_reset_token') || !session('password_reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password-with-code');
    }

    /**
     * Cambia la contraseña del usuario
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        if (!session('password_reset_token') || !session('password_reset_email')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = session('password_reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Usuario no encontrado.']);
        }

        // Actualiza la contraseña
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Limpia la sesión
        session()->forget(['password_reset_token', 'password_reset_email']);

        return redirect()->route('login')->with('status', 'Tu contraseña ha sido cambiada exitosamente.');
    }

    /**
     * Envía el correo con el código de verificación
     */
    private function sendCodeEmail(string $email, string $code): void
    {
        $data = [
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(15)->format('H:i'),
        ];

        Mail::send('emails.password-reset-code', $data, function ($message) use ($email) {
            $message->to($email)
                    ->subject('Código de Recuperación de Contraseña');
        });
    }
}
