<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor, ingresa un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $email = $this->string('email');
        $password = $this->string('password');

        // Verificar si el usuario existe
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Si el email no existe, mostrar error en el campo email
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'email' => 'No encontramos una cuenta con este correo electrónico. Verifica que esté escrito correctamente.',
            ]);
        }

        // Verificar si la cuenta está activa (si tienes un campo de estado)
        if (isset($user->estado) && $user->estado !== 'activo') {
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador para más información.',
            ]);
        }

        // Verificar si el usuario ya tiene una sesión activa
        if ($this->hasActiveSession($user->id)) {
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'email' => 'Ya hay una sesión iniciada con esta cuenta en otro navegador o pestaña. Por favor, cierra la sesión anterior antes de iniciar una nueva.',
            ]);
        }

        // Si el usuario existe, intentar autenticar
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Si la autenticación falla (contraseña incorrecta), mostrar error en el campo password
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'La contraseña es incorrecta. Por favor, inténtalo de nuevo.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Verificar si el usuario tiene una sesión activa
     */
    protected function hasActiveSession($userId): bool
    {
        try {
            $sessionsTable = config('session.table', 'sessions');
            $sessionLifetime = config('session.lifetime', 120);
            
            // Verificar si existe la columna user_id en la tabla de sesiones
            $hasUserIdColumn = DB::getSchemaBuilder()->hasColumn($sessionsTable, 'user_id');
            
            if ($hasUserIdColumn) {
                // Buscar sesiones activas del usuario
                // Una sesión se considera activa si:
                // 1. Tiene user_id asignado
                // 2. Su last_activity es reciente (dentro del tiempo de vida de la sesión)
                $activeSessions = DB::table($sessionsTable)
                    ->where('user_id', $userId)
                    ->whereNotNull('user_id')
                    ->where('last_activity', '>', now()->subMinutes($sessionLifetime)->timestamp)
                    ->count();
                
                return $activeSessions > 0;
            } else {
                // Si no hay columna user_id, buscar sesiones por el payload
                $sessions = DB::table($sessionsTable)
                    ->where('last_activity', '>', now()->subMinutes($sessionLifetime)->timestamp)
                    ->get();
                
                foreach ($sessions as $session) {
                    try {
                        $payload = unserialize(base64_decode($session->payload));
                        
                        // Buscar el ID del usuario en el payload
                        $userFound = false;
                        
                        // Buscar en login_web_*
                        foreach ($payload as $key => $value) {
                            if (is_string($key) && strpos($key, 'login_web_') !== false) {
                                // Verificar si el valor contiene el user_id
                                if ($value == $userId || (is_array($value) && isset($value['id']) && $value['id'] == $userId)) {
                                    $userFound = true;
                                    break;
                                }
                            }
                        }
                        
                        if ($userFound) {
                            return true;
                        }
                    } catch (\Exception $e) {
                        // Si hay error al decodificar, continuar con la siguiente
                        continue;
                    }
                }
            }
            
            return false;
        } catch (\Exception $e) {
            // Si hay algún error, permitir el login (no bloquear por error técnico)
            \Log::warning('Error al verificar sesiones activas: ' . $e->getMessage());
            return false;
        }
    }
}
