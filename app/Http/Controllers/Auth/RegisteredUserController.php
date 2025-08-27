<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validar los datos
            $request->validate([
                'primer_nombre' => ['required', 'string', 'max:255'],
                'segundo_nombre' => ['nullable', 'string', 'max:255'],
                'primer_apellido' => ['required', 'string', 'max:255'],
                'segundo_apellido' => ['required', 'string', 'max:255'],
                'entidad' => ['required', 'string', 'in:funadpas,fundacion_isaias,diocesis_apartado,pastoral_social'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                'primer_nombre.required' => 'El primer nombre es obligatorio.',
                'primer_apellido.required' => 'El primer apellido es obligatorio.',
                'segundo_apellido.required' => 'El segundo apellido es obligatorio.',
                'entidad.required' => 'Debes seleccionar una entidad.',
                'entidad.in' => 'La entidad seleccionada no es válida.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El formato del correo electrónico no es válido.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
            ]);

            // Crear el usuario
            $user = User::create([
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'entidad' => $request->entidad,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Autenticar al usuario
            Auth::login($user);

            // Generar token para mensaje flash
            $messageToken = Str::random(32);

            // Redirigir al dashboard con mensaje de éxito
            return redirect()->route('dashboard')
                           ->with('flash_message', '¡Bienvenido a la Diócesis de Apartadó! Tu cuenta ha sido creada exitosamente.')
                           ->with('flash_token', $messageToken)
                           ->with('success', true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación, redirigir de vuelta con los errores
            return back()
                ->withErrors($e->validator)
                ->withInput($request->except('password', 'password_confirmation'));
        } catch (\Exception $e) {
            // Si hay algún otro error, redirigir con mensaje genérico
            return back()
                ->withErrors(['general' => 'Ha ocurrido un error durante el registro. Por favor, intenta nuevamente.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
