<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Ruta temporal para test final
Route::get('/test', function () {
    return view('test-final');
})->name('test');

// Ruta de prueba para navigation loading
Route::get('/test-navigation', function () {
    return view('test-navigation');
})->name('test-navigation');

// Rutas temporales para debugging móvil (solo para testing)
Route::get('/check-mobile-session', function () {
    return response()->json([
        'status' => 'info',
        'session_id' => session()->getId(),
        'invitation_email' => session('invitation_email'),
        'invitation_code_id' => session('invitation_code_id'),
        'has_invitation_email' => session()->has('invitation_email'),
        'has_invitation_code_id' => session()->has('invitation_code_id'),
        'user_agent' => request()->userAgent()
    ]);
});

Route::get('/clear-mobile-session', function () {
    session()->forget(['invitation_email', 'invitation_code_id', 'invitation_status']);
    return response()->json([
        'status' => 'success',
        'message' => 'Sesión de invitación limpiada desde móvil',
        'session_id' => session()->getId(),
        'invitation_email' => session('invitation_email'),
        'invitation_code_id' => session('invitation_code_id')
    ]);
});

// Ruta temporal para actualizar sesión existente
Route::get('/fix-session', function () {
    if (session('invitation_email') && session('invitation_code_id')) {
        session(['invitation_status' => 'verified']);
        return response()->json([
            'status' => 'success',
            'message' => 'Sesión actualizada con status verified',
            'invitation_email' => session('invitation_email'),
            'invitation_code_id' => session('invitation_code_id'),
            'invitation_status' => session('invitation_status')
        ]);
    }
    return response()->json(['status' => 'error', 'message' => 'No hay sesión de invitación activa']);
});

// Verificación de código de invitación (público)
Route::get('/verify-invitation', [InvitationController::class, 'show'])->name('invitation.verify');
Route::post('/verify-invitation', [InvitationController::class, 'verify'])->name('invitation.verify');

// Verificación de conexión a la base de datos (público)
Route::get('/check-database-connection', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return response()->json([
            'status' => 'success',
            'message' => 'Conexión a la base de datos exitosa',
            'timestamp' => now()->toISOString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'No se puede conectar a la base de datos',
            'error' => $e->getMessage(),
            'timestamp' => now()->toISOString()
        ], 500);
    }
})->name('check.database');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Con Autenticación)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.custom'])->group(function () {
    // Ruta para limpiar sesión de bienvenida
    Route::post('/dashboard/clear-welcome-session', function () {
        session()->forget('welcome_message');
        return response()->json(['success' => true]);
    })->name('dashboard.clear-welcome-session');
    
    // Dashboard principal (nuevo layout)
    Route::get('/dashboard', function () {
        // Log de depuración
        \Log::info('=== DASHBOARD ACCEDIDO ===');
        \Log::info('Usuario: ' . auth()->user()->primer_nombre);
        \Log::info('Rol: ' . auth()->user()->rol);
        \Log::info('Dashboard visited: ' . (session()->has('dashboard_visited') ? 'true' : 'false'));
        \Log::info('Welcome message en sesión: ' . (session()->has('welcome_message') ? 'true' : 'false'));
        \Log::info('Todas las variables de sesión: ' . json_encode(session()->all()));
        
        // Verificar si hay un mensaje de bienvenida especial (registro)
        if (session()->has('welcome_message')) {
            $welcomeMessage = session('welcome_message');
            session()->put('dashboard_visited', true); // Marcar como visitado
            
            \Log::info('Mostrando mensaje de bienvenida especial (registro): ' . $welcomeMessage);
            return view('dashboard-new')->with('welcome_message', $welcomeMessage);
        }
        
        // Verificar si es la primera vez que el usuario accede al dashboard en esta sesión
        if (!session()->has('dashboard_visited')) {
            session()->put('dashboard_visited', true);
            // Mensaje para visitas posteriores (login)
            $welcomeMessage = '¡Qué bueno verte por aquí otra vez, ' . auth()->user()->primer_nombre . '!';
            \Log::info('Mostrando mensaje de bienvenida (primera vez en sesión): ' . $welcomeMessage);
            
            // Enviar la vista con el mensaje de bienvenida
            return view('dashboard-new')->with('welcome_message', $welcomeMessage);
        }
        
        // Verificar si hay un parámetro para forzar la alerta (solo para testing)
        if (request()->has('force_welcome') && request()->get('force_welcome') === '1') {
            $welcomeMessage = '¡Mensaje de prueba forzado, ' . auth()->user()->primer_nombre . '!';
            \Log::info('Mostrando mensaje de bienvenida FORZADO: ' . $welcomeMessage);
            return view('dashboard-new')->with('welcome_message', $welcomeMessage);
        }
        
        // Si ya visitó antes en esta sesión, no mostrar mensaje
        \Log::info('Dashboard ya visitado en esta sesión, no mostrando mensaje de bienvenida');
        return view('dashboard-new');
    })->name('dashboard');
    
    // Perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de Cursos
    Route::resource('cursos', CursoController::class);
    
    // Rutas de Administración - Solo para administradores
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        // Ruta para limpiar sesión de bienvenida en admin
        Route::post('/clear-welcome-session', function () {
            session()->forget('welcome_message');
            return response()->json(['success' => true]);
        })->name('clear-welcome-session');
        
        Route::get('/panel', function () {
            // Preparar datos para el panel de administración
            $activeCodes = \App\Models\InvitationCode::where('used', false)
                ->where('expires_at', '>', now())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            // Para el historial, obtenemos códigos que ya no están activos (usados o expirados)
            $historyCodes = \App\Models\InvitationCode::where(function($query) {
                $query->where('used', true)
                      ->orWhere('expires_at', '<', now());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
            // Verificar si hay un mensaje de bienvenida especial (registro)
            if (session()->has('welcome_message')) {
                $welcomeMessage = session('welcome_message');
                session()->put('dashboard_visited', true); // Marcar como visitado
                
                return view('admin.panel-new', compact('activeCodes', 'historyCodes'))->with('welcome_message', $welcomeMessage);
            }
            
            // Verificar si es la primera vez que el usuario accede al dashboard en esta sesión
            if (!session()->has('dashboard_visited')) {
                session()->put('dashboard_visited', true);
                // Mensaje para visitas posteriores (login)
                $welcomeMessage = '¡Qué bueno verte por aquí otra vez, ' . auth()->user()->primer_nombre . '!';
                
                return view('admin.panel-new', compact('activeCodes', 'historyCodes'))->with('welcome_message', $welcomeMessage);
            }
            
            return view('admin.panel-new', compact('activeCodes', 'historyCodes'));
        })->name('panel');
        Route::get('/', function () {
            return redirect()->route('admin.panel');
        }); // Redirigir /admin a /admin/panel
        Route::post('/send-invitations', [AdminController::class, 'sendInvitations'])->name('send-invitations');
        Route::delete('/delete-invitation/{invitationCode}', [AdminController::class, 'deleteInvitation'])->name('delete-invitation');
        
        // Rutas de búsqueda AJAX
        Route::post('/search-active-codes', [AdminController::class, 'searchActiveCodes'])->name('search-active-codes');
        Route::post('/search-history-codes', [AdminController::class, 'searchHistoryCodes'])->name('search-history-codes');
        
        // Ruta temporal para debugging
        Route::post('/debug-send', function(Request $request) {
            return response()->json([
                'received_data' => $request->all(),
                'emails' => $request->input('emails'),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'is_ajax' => $request->ajax(),
                'expects_json' => $request->expectsJson()
            ]);
        })->name('debug-send');
    });
});

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';