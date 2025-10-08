<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InvitationCode;
use App\Mail\WelcomeMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Requests\DocumentValidationRequest;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * 
     * Las verificaciones de autenticación y código de invitación se manejan
     * en el middleware 'invitation.verified'
     */
    public function create()
    {
        return view('auth.register', [
            'invitation_email' => session('invitation_email')
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Verificación adicional de seguridad (por si se saltara el middleware)
            if (!session('invitation_email') || !session('invitation_code_id')) {
                return redirect()->route('invitation.verify')
                    ->with('error', 'Sesión de invitación inválida. Por favor, verifica tu código de invitación nuevamente.');
            }

            // Validar los datos básicos con validaciones mejoradas
            $request->validate([
                'primer_nombre' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\'-]+$/'],
                'segundo_nombre' => ['nullable', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\'-]+$/'],
                'primer_apellido' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\'-]+$/'],
                'segundo_apellido' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\'-]+$/'],
                'tipo_documento' => ['required', 'string', 'in:CC,CE,TI,PP,NIT'],
                'numero_documento' => ['required', 'string', 'max:20', 'unique:users,numero_documento', 'regex:/^[0-9A-Za-z]+$/'],
                'entidad' => ['required', 'string', 'in:funadpas,fundacion_isaias,diocesis_apartado,pastoral_social'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'],
                'password' => ['required', 'confirmed', 'min:8'],
            ], [
                // Mensajes para nombres
                'primer_nombre.required' => 'El primer nombre es obligatorio.',
                'primer_nombre.min' => 'El primer nombre debe tener al menos 2 caracteres.',
                'primer_nombre.max' => 'El primer nombre no puede tener más de 50 caracteres.',
                'primer_nombre.regex' => 'El primer nombre solo puede contener letras, espacios, acentos y guiones.',
                
                'segundo_nombre.min' => 'El segundo nombre debe tener al menos 2 caracteres.',
                'segundo_nombre.max' => 'El segundo nombre no puede tener más de 50 caracteres.',
                'segundo_nombre.regex' => 'El segundo nombre solo puede contener letras, espacios, acentos y guiones.',
                
                'primer_apellido.required' => 'El primer apellido es obligatorio.',
                'primer_apellido.min' => 'El primer apellido debe tener al menos 2 caracteres.',
                'primer_apellido.max' => 'El primer apellido no puede tener más de 50 caracteres.',
                'primer_apellido.regex' => 'El primer apellido solo puede contener letras, espacios, acentos y guiones.',
                
                'segundo_apellido.required' => 'El segundo apellido es obligatorio.',
                'segundo_apellido.min' => 'El segundo apellido debe tener al menos 2 caracteres.',
                'segundo_apellido.max' => 'El segundo apellido no puede tener más de 50 caracteres.',
                'segundo_apellido.regex' => 'El segundo apellido solo puede contener letras, espacios, acentos y guiones.',
                
                // Mensajes para documento
                'tipo_documento.required' => 'El tipo de documento es obligatorio.',
                'tipo_documento.in' => 'El tipo de documento seleccionado no es válido.',
                
                'numero_documento.required' => 'El número de documento es obligatorio.',
                'numero_documento.max' => 'El número de documento no puede tener más de 20 caracteres.',
                'numero_documento.unique' => 'Este número de documento ya está registrado.',
                'numero_documento.regex' => 'El número de documento solo puede contener números y letras.',
                
                // Mensajes para entidad
                'entidad.required' => 'Debes seleccionar una entidad.',
                'entidad.in' => 'La entidad seleccionada no es válida.',
                
                // Mensajes para email
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El formato del correo electrónico no es válido.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
                'email.regex' => 'El formato del correo electrónico no es válido.',
                
                // Mensajes para contraseña
                'password.required' => 'La contraseña es obligatoria.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ]);

            // Validar documento colombiano
            $documentValidator = new DocumentValidationRequest();
            $documentValidator->merge($request->only(['tipo_documento', 'numero_documento']));
            
            if (!$documentValidator->validateColombianDocument($request->tipo_documento, $request->numero_documento)) {
                return back()
                    ->withErrors(['numero_documento' => 'El número de documento no es válido para el tipo seleccionado.'])
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            // Verificar que el email coincide con el código de invitación
            if ($request->email !== session('invitation_email')) {
                return back()
                    ->withErrors(['email' => 'El correo electrónico debe coincidir con el código de invitación.'])
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            // Limpiar y normalizar los datos antes de crear el usuario
            $user = User::create([
                'primer_nombre' => trim($request->primer_nombre),
                'segundo_nombre' => $request->segundo_nombre ? trim($request->segundo_nombre) : null,
                'primer_apellido' => trim($request->primer_apellido),
                'segundo_apellido' => trim($request->segundo_apellido),
                'tipo_documento' => strtoupper($request->tipo_documento),
                'numero_documento' => strtoupper(trim($request->numero_documento)),
                'entidad' => $request->entidad,
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'rol' => 'estudiante', // Rol por defecto para nuevos usuarios
            ]);

            // Marcar el código de invitación como usado
            $invitationCode = InvitationCode::find(session('invitation_code_id'));
            if ($invitationCode) {
                $invitationCode->markAsUsed();
            }

            // Limpiar la sesión de invitación completamente
            session()->forget(['invitation_email', 'invitation_code_id', 'invitation_verified_at', 'invitation_status']);

            // Enviar correo de bienvenida
            try {
                Mail::to($user->email)->send(new WelcomeMail($user));
            } catch (\Exception $e) {
                // Si falla el envío del correo, continuar con el registro
                // El usuario ya está registrado, solo no recibirá el correo
                \Log::error('Error enviando correo de bienvenida: ' . $e->getMessage());
            }

            // Autenticar al usuario
            Auth::login($user);

            // Generar token para mensaje flash
            $messageToken = Str::random(32);

            // Limpiar las sesiones de visitas para mostrar mensaje de bienvenida especial
            $request->session()->forget('dashboard_visited');
            $request->session()->forget('admin_panel_visited');
            
            // Redirigir al dashboard con mensaje de bienvenida para nuevo usuario
            return redirect()->route('dashboard')
                           ->with('welcome_message', '¡Bienvenido a la Diócesis de Apartadó, ' . $user->primer_nombre . '! Tu cuenta ha sido creada exitosamente.');

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
