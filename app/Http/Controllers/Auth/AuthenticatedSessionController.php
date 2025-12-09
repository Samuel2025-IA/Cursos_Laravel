<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

            // Obtener el usuario autenticado
            $user = Auth::user();
            
            // Ya no invalidamos sesiones anteriores, solo permitimos login si no hay sesiones activas
            // La verificación se hace en LoginRequest antes de autenticar

            $request->session()->regenerate();
            
            // El user_id se guardará automáticamente mediante el Event Listener
            // No es necesario llamar a saveUserIdToSession aquí

            // Limpiar las sesiones de visitas para mostrar mensaje de bienvenida
            $request->session()->forget('dashboard_visited');
            
            // Solo limpiar admin_panel_visited si no existe (primera vez en esta sesión)
            if (!$request->session()->has('admin_panel_visited')) {
                // No hacer nada, dejar que se establezca cuando visite el panel
            } else {
                // Si ya existe, mantenerlo para mostrar "hola de nuevo"
                \Log::info('Manteniendo admin_panel_visited para mostrar mensaje de visita recurrente');
            }
            
            \Log::info('=== LOGIN EXITOSO ===');
            \Log::info('Usuario: ' . Auth::user()->primer_nombre);
            \Log::info('Rol: ' . Auth::user()->rol);
            \Log::info('Limpiando sesiones de visitas para mostrar mensaje de bienvenida');
            \Log::info('Sesión después del login: ' . json_encode($request->session()->all()));
            
            // Determinar mensaje de bienvenida
            $user = Auth::user();
            $isFirstTime = !$request->session()->has('user_has_visited_before');
            
            if ($isFirstTime) {
                // Primera vez del usuario
                $request->session()->put('user_has_visited_before', true);
            }
            
            // Mensaje de bienvenida único para todos
            $welcomeMessage = '¡Qué bueno verte por aquí ' . $user->primer_nombre . '!';
            \Log::info('Mostrando mensaje de bienvenida: ' . $welcomeMessage);
            
            return redirect()->intended(route('dashboard', absolute: false))
                ->with('welcome_message', $welcomeMessage);
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
        // Obtener el ID del usuario antes de hacer logout
        $userId = Auth::id();
        
        // Marcar como logout legítimo en localStorage antes de hacer logout
        // Esto se hará mediante JavaScript en el frontend
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        // Eliminar el user_id de la sesión para permitir nuevo login
        if ($userId) {
            $this->clearSessionUserId($userId);
        }

        return redirect('/')->with([
            'logout_success' => true,
            'goodbye' => 'Te agradecemos por haber visitado nuestra página. ¡Te esperamos pronto!'
        ]);
    }
    
    /**
     * Limpiar el user_id de las sesiones del usuario al hacer logout
     */
    protected function clearSessionUserId($userId): void
    {
        try {
            $sessionsTable = config('session.table', 'sessions');
            
            // Verificar si existe la columna user_id
            $hasUserIdColumn = DB::getSchemaBuilder()->hasColumn($sessionsTable, 'user_id');
            
            if ($hasUserIdColumn) {
                // Eliminar el user_id de todas las sesiones del usuario
                // Esto permite que el usuario pueda iniciar sesión nuevamente
                DB::table($sessionsTable)
                    ->where('user_id', $userId)
                    ->update(['user_id' => null]);
            }
        } catch (\Exception $e) {
            // Si hay algún error, registrar pero no fallar el logout
            \Log::warning('Error al limpiar user_id de sesión: ' . $e->getMessage());
        }
    }

    /**
     * Invalidar todas las sesiones activas del usuario excepto la actual
     */
    protected function invalidateOtherSessions($userId): void
    {
        try {
            $sessionsTable = config('session.table', 'sessions');
            
            // Verificar si existe la columna user_id en la tabla de sesiones
            $hasUserIdColumn = DB::getSchemaBuilder()->hasColumn($sessionsTable, 'user_id');
            
            if ($hasUserIdColumn) {
                // Eliminar todas las sesiones del usuario
                // Esto se hace ANTES de regenerar la sesión, así que todas las anteriores se eliminan
                DB::table($sessionsTable)
                    ->where('user_id', $userId)
                    ->delete();
            } else {
                // Si no hay columna user_id, buscar sesiones por el payload
                // Esto es menos eficiente pero funciona si la tabla no tiene user_id
                $sessions = DB::table($sessionsTable)->get();
                
                foreach ($sessions as $session) {
                    try {
                        $payload = unserialize(base64_decode($session->payload));
                        
                        // Buscar el ID del usuario en el payload de diferentes formas
                        $userFound = false;
                        
                        // Forma 1: Buscar en login_web_59b316adc4f93
                        foreach ($payload as $key => $value) {
                            if (is_string($key) && strpos($key, 'login_web_') !== false) {
                                // Verificar si el valor contiene el user_id
                                if ($value == $userId || (is_array($value) && isset($value['id']) && $value['id'] == $userId)) {
                                    $userFound = true;
                                    break;
                                }
                            }
                        }
                        
                        // Forma 2: Buscar directamente en el payload
                        if (!$userFound && isset($payload['login_web_' . config('auth.defaults.guard') . '_' . $userId])) {
                            $userFound = true;
                        }
                        
                        if ($userFound) {
                            // Esta es una sesión del usuario, eliminarla
                            DB::table($sessionsTable)
                                ->where('id', $session->id)
                                ->delete();
                        }
                    } catch (\Exception $e) {
                        // Si hay error al decodificar, continuar con la siguiente
                        continue;
                    }
                }
            }
        } catch (\Exception $e) {
            // Si hay algún error, registrar pero no fallar el login
            \Log::warning('Error al invalidar sesiones anteriores: ' . $e->getMessage());
        }
    }

}
