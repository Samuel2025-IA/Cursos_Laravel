<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        // Si el usuario ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            // Limpiar las sesiones de visitas para mostrar mensaje de bienvenida
            $request->session()->forget('dashboard_visited');
            $request->session()->forget('admin_panel_visited');
            
            \Log::info('=== LOGIN EXITOSO ===');
            \Log::info('Usuario: ' . Auth::user()->primer_nombre);
            \Log::info('Rol: ' . Auth::user()->rol);
            \Log::info('Limpiando sesiones de visitas para mostrar mensaje de bienvenida');
            \Log::info('Sesión después del login: ' . json_encode($request->session()->all()));
            
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación, redirigir con alerta de error
            $errors = $e->errors();
            $errorType = 'general';
            $errorMessage = '';
            
            if (isset($errors['email'])) {
                $errorType = 'email';
                $errorMessage = $errors['email'][0];
            } elseif (isset($errors['password'])) {
                $errorType = 'password';
                $errorMessage = $errors['password'][0];
            } else {
                $errorType = 'general';
                $errorMessage = 'Error de autenticación. Por favor, verifica tus credenciales.';
            }
            
            // Mantener el campo que está correcto, solo limpiar el que tiene error
            $inputData = [];
            if ($errorType === 'email') {
                // Si el error es de email, mantener la contraseña
                $inputData['password'] = $request->input('password');
            } else if ($errorType === 'password') {
                // Si el error es de contraseña, mantener el email
                $inputData['email'] = $request->input('email');
            }
            
            return redirect()->back()
                ->with('error_type', $errorType)
                ->with('error_message', $errorMessage)
                ->withInput($inputData);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with([
            'logout_success' => true,
            'goodbye' => 'Te agradecemos por haber visitado nuestra página. ¡Te esperamos pronto!'
        ]);
    }
}
