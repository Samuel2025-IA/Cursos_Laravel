<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RecursoController;
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

// Ruta para limpiar sesión de admin welcome (solo para testing)
Route::get('/clear-admin-welcome', function () {
    session()->forget(['admin_welcome_shown']);
    return response()->json([
        'status' => 'success',
        'message' => 'Sesión de admin welcome limpiada',
        'admin_welcome_shown' => session('admin_welcome_shown')
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

// Ruta para obtener token CSRF (público)
Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
})->name('csrf.token');

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
    
    // Ruta temporal para probar alertas del dashboard
    Route::get('/dashboard/test-alert', function () {
        $welcomeMessage = '¡Esta es una alerta de prueba para admin, ' . auth()->user()->primer_nombre . '!';
        return redirect()->route('dashboard')->with('welcome_message', $welcomeMessage);
    })->name('dashboard.test-alert');
    
    // Ruta temporal para probar alerta de bienvenida normal
    Route::get('/dashboard/test-welcome', function () {
        $welcomeMessage = '¡Qué bueno verte por aquí otra vez, ' . auth()->user()->primer_nombre . '! ¡Bienvenido al sistema de cursos de la Diócesis de Apartadó!';
        return redirect()->route('dashboard')->with('welcome_message', $welcomeMessage);
    })->name('dashboard.test-welcome');
    
    // Ruta temporal para simular primera vez del usuario
    Route::get('/dashboard/test-first-time', function () {
        session()->forget('user_has_visited_before');
        session()->forget('dashboard_visited');
        return redirect()->route('dashboard')->with('success', 'Simulando primera vez del usuario');
    })->name('dashboard.test-first-time');
    
    // Ruta temporal para simular visita posterior
    Route::get('/dashboard/test-return', function () {
        session()->put('user_has_visited_before', true);
        session()->forget('dashboard_visited');
        return redirect()->route('dashboard')->with('success', 'Simulando visita posterior');
    })->name('dashboard.test-return');
    
    // Ruta temporal para limpiar sesión del dashboard
    Route::get('/dashboard/clear-session', function () {
        session()->forget('dashboard_visited');
        session()->forget('admin_welcome_shown');
        session()->forget('user_welcome_shown');
        session()->forget('user_has_visited_before');
        return redirect()->route('dashboard')->with('success', 'Sesión del dashboard limpiada');
    })->name('dashboard.clear-session');
    
    // Ruta de prueba para verificar alertas
    Route::get('/dashboard/test-debug', function () {
        $user = auth()->user();
        $debugInfo = [
            'user_name' => $user->primer_nombre,
            'user_role' => $user->rol,
            'session_welcome_message' => session('welcome_message'),
            'session_dashboard_visited' => session('dashboard_visited'),
            'all_session_data' => session()->all()
        ];
        
        return response()->json([
            'message' => 'Debug info del dashboard',
            'data' => $debugInfo,
            'should_show_alert' => $user->rol === 'admin'
        ]);
    })->name('dashboard.test-debug');
    
    // Dashboard principal (nuevo layout)
    Route::get('/dashboard', function () {
        $prepareDashboardData = function (array $additional = []) {
            $today = \Illuminate\Support\Carbon::today();
            $sevenDaysAgo = \Illuminate\Support\Carbon::today()->subDays(7);

            // Cursos finalizados (caducados)
            $finalizedCoursesCollection = \App\Models\Curso::query()
                ->whereNotNull('fecha_fin')
                ->whereDate('fecha_fin', '<=', $today)
                ->orderByDesc('fecha_fin')
                ->limit(5)
                ->get();

            // Cursos nuevos (creados en los últimos 7 días)
            $newCourses = \App\Models\Curso::query()
                ->where('created_at', '>=', $sevenDaysAgo)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            // Agregar notificaciones de cursos finalizados
            $finalizedNotifications = $finalizedCoursesCollection->map(function (\App\Models\Curso $curso) {
                return [
                    'id' => 'finalized_' . $curso->id,
                    'curso_id' => $curso->id,
                    'nombre' => $curso->nombre,
                    'estado' => $curso->estado,
                    'fecha' => optional($curso->fecha_fin)->format('d/m/Y'),
                    'tipo' => 'finalizado',
                    'mensaje' => 'Finalizado el ' . optional($curso->fecha_fin)->format('d/m/Y'),
                ];
            });

            // Agregar notificaciones de cursos nuevos
            $newNotifications = $newCourses->map(function (\App\Models\Curso $curso) {
                return [
                    'id' => 'new_' . $curso->id,
                    'curso_id' => $curso->id,
                    'nombre' => $curso->nombre,
                    'estado' => $curso->activo ? 'Activo' : 'Inactivo',
                    'fecha' => $curso->created_at->format('d/m/Y'),
                    'tipo' => 'nuevo',
                    'mensaje' => 'Creado el ' . $curso->created_at->format('d/m/Y'),
                ];
            });

            // Combinar ambas notificaciones y ordenar por fecha real (más recientes primero)
            $notifications = $finalizedNotifications->concat($newNotifications)
                ->map(function ($notification) use ($newCourses, $finalizedCoursesCollection) {
                    // Agregar timestamp para ordenamiento
                    if ($notification['tipo'] === 'nuevo') {
                        // Para cursos nuevos, usar la fecha de creación
                        $curso = $newCourses->firstWhere('id', $notification['curso_id']);
                        $notification['timestamp'] = $curso ? $curso->created_at->timestamp : 0;
                    } else {
                        // Para cursos finalizados, usar la fecha de finalización
                        $curso = $finalizedCoursesCollection->firstWhere('id', $notification['curso_id']);
                        $notification['timestamp'] = $curso && $curso->fecha_fin ? $curso->fecha_fin->timestamp : 0;
                    }
                    return $notification;
                })
                ->sortByDesc('timestamp')
                ->take(10) // Limitar a 10 notificaciones
                ->values();

            $stats = [
                'total_cursos' => \App\Models\Curso::count(),
                'cursos_activos' => \App\Models\Curso::where('activo', true)->count(),
                'cursos_finalizados' => $finalizedCoursesCollection->count(),
                'usuarios_registrados' => \App\Models\User::count(),
                'codigos_activos' => auth()->user()->rol === 'admin'
                    ? \App\Models\InvitationCode::where('used', false)->where('expires_at', '>', now())->count()
                    : 0,
            ];

            $recentCourses = \App\Models\Curso::latest()->limit(5)->get();

            return array_merge($additional, [
                'statsFromRoute' => $stats,
                'recentCoursesFromRoute' => $recentCourses,
                'dashboardNotifications' => $notifications,
                'notificationsCount' => $notifications->count(),
            ]);
        };

        // Log de depuración
        \Log::info('=== DASHBOARD ACCEDIDO ===');
        \Log::info('Usuario: ' . auth()->user()->primer_nombre);
        \Log::info('Rol: ' . auth()->user()->rol);
        \Log::info('Dashboard visited: ' . (session()->has('dashboard_visited') ? 'true' : 'false'));
        \Log::info('Welcome message en sesión: ' . (session()->has('welcome_message') ? 'true' : 'false'));
        \Log::info('Todas las variables de sesión: ' . json_encode(session()->all()));
        
        // Lógica para admin: mostrar bienvenida SOLO una vez por sesión (al iniciar sesión)
        // Tiene prioridad sobre el welcome_message general
        if (auth()->user()->rol === 'admin') {
            if (!session()->has('admin_welcome_shown')) {
                $welcomeMessage = '¡Qué bueno verte por aquí ' . auth()->user()->primer_nombre . '!';
                
                \Log::info('Mostrando mensaje de bienvenida para admin (primera visita en sesión): ' . $welcomeMessage);
                
                // Marcar como mostrado para no repetir durante la sesión
                session()->put('admin_welcome_shown', true);
                
                return view('dashboard-new', $prepareDashboardData([
                    'admin_welcome_message' => $welcomeMessage,
                ]));
            }
        }
        
        // Verificar si hay un mensaje de bienvenida (desde login o registro)
        if (session()->has('welcome_message')) {
            $welcomeMessage = session('welcome_message');
            
            // NO poner dashboard_visited aquí porque queremos que se muestre la alerta
            // El welcome-goodbye-alerts.js manejará esto
            
            \Log::info('🔔 Welcome message encontrado en sesión: ' . $welcomeMessage);
            \Log::info('🔔 Pasando welcome_message a la vista...');
            
            // Solo pasar a la vista, no hacer cambios en la sesión todavía
            return view('dashboard-new', $prepareDashboardData());
        }
        
        // Verificar si es la primera vez que el usuario accede al dashboard en esta sesión
        \Log::info('🔍 Dashboard visited check: ' . (session()->has('dashboard_visited') ? 'true' : 'false'));
        if (!session()->has('dashboard_visited')) {
            session()->put('dashboard_visited', true);
            
            // Determinar si es primera vez del usuario o visita posterior
            $user = auth()->user();
            $isFirstTime = !session()->has('user_has_visited_before');
            
            if ($isFirstTime) {
                // Primera vez del usuario
                session()->put('user_has_visited_before', true);
            }
            
            // Mensaje único para todos
            $welcomeMessage = '¡Qué bueno verte por aquí ' . $user->primer_nombre . '!';
            \Log::info('Mostrando mensaje de bienvenida: ' . $welcomeMessage);
            
            // Enviar la vista con el mensaje de bienvenida
            return view('dashboard-new', $prepareDashboardData([
                'welcome_message' => $welcomeMessage,
            ]));
        } else {
            \Log::info('🔍 Dashboard ya visitado en esta sesión, no mostrando mensaje de bienvenida');
        }
        
        // Si ya visitó antes en esta sesión, no mostrar mensaje para usuarios normales
        \Log::info('Dashboard ya visitado en esta sesión, no mostrando mensaje de bienvenida para usuario normal');
        return view('dashboard-new', $prepareDashboardData());
    })->name('dashboard');
    
    // Perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de creación de cursos - Solo para administradores (ANTES del resource)
    Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create');
    Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store');
    
    // Rutas de Cursos
    Route::resource('cursos', CursoController::class)->except(['create', 'store']);
    
    // Rutas para estudiantes - Iniciar y completar curso
    Route::get('/cursos/{curso}/iniciar', [CursoController::class, 'iniciar'])->name('cursos.iniciar');
    Route::post('/cursos/{curso}/completar', [CursoController::class, 'completar'])->name('cursos.completar');
    
    // Rutas para admin - Ver resultados de cursos
    Route::get('/cursos/{curso}/resultados', [CursoController::class, 'resultados'])->name('cursos.resultados');
    
    // Rutas de Recursos
    Route::get('/recursos', [RecursoController::class, 'index'])->name('recursos.index');
    Route::get('/recursos/protocolos', [RecursoController::class, 'protocolos'])->name('recursos.protocolos');
    Route::get('/recursos/{recurso}/download', [RecursoController::class, 'download'])->name('recursos.download');
    
    // Rutas de Recursos - Solo para administradores
    Route::middleware('admin')->group(function () {
        Route::get('/recursos/create', [RecursoController::class, 'create'])->name('recursos.create');
        Route::post('/recursos', [RecursoController::class, 'store'])->name('recursos.store');
        Route::delete('/recursos/{recurso}', [RecursoController::class, 'destroy'])->name('recursos.destroy');
    });
    
    Route::middleware('admin')->group(function () {
        
        // Ruta de prueba temporal
        Route::get('/test-cursos', function () {
            return response()->json([
                'message' => 'Ruta de cursos funcionando',
                'user' => auth()->user()->email,
                'rol' => auth()->user()->rol
            ]);
        })->name('test.cursos');
    });

    // Servir archivos del disco public a través de Laravel (evita problemas en algunos entornos)
    Route::get('/media/{path}', [\App\Http\Controllers\MediaController::class, 'show'])
        ->where('path', '.*')
        ->name('media.show');
    
    // Rutas de Administración - Solo para administradores
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        // Ruta para limpiar sesión de bienvenida en admin
        Route::post('/clear-welcome-session', function () {
            session()->forget('welcome_message');
            return response()->json(['success' => true]);
        })->name('clear-welcome-session');
        
        
        
        Route::get('/panel', [App\Http\Controllers\AdminController::class, 'index'])->name('panel');
        Route::get('/', function () {
            return redirect()->route('admin.panel');
        }); // Redirigir /admin a /admin/panel
        Route::post('/send-invitations', [AdminController::class, 'sendInvitations'])->name('send-invitations');
        Route::post('/send-bulk-invitations', [AdminController::class, 'sendBulkInvitations'])->name('send-bulk-invitations');
        Route::get('/download-template', [AdminController::class, 'downloadTemplate'])->name('download-template');
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