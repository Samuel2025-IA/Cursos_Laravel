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
    // Dashboard principal
    Route::get('/dashboard', function () {
        $cursos = \App\Models\Curso::latest()->limit(6)->get();
        
        // Preparar datos para admin
        $invitationCodes = collect(); // Inicializar como colección vacía por defecto
        if (auth()->user()->rol === 'admin') {
            // Obtener códigos activos
            $activeCodes = \App\Models\InvitationCode::orderBy('created_at', 'desc')->get();
            
            // Obtener historial de códigos eliminados
            $historyCodes = \App\Models\InvitationCodeHistory::orderBy('created_at', 'desc')->get();
            
            // Combinar ambos en una sola colección
            $allCodes = collect();
            
            // Agregar códigos activos
            foreach ($activeCodes as $code) {
                $allCodes->push((object) [
                    'id' => $code->id,
                    'email' => $code->email,
                    'code' => $code->code,
                    'used' => $code->used,
                    'expires_at' => $code->expires_at,
                    'created_at' => $code->created_at,
                    'updated_at' => $code->updated_at,
                    'is_active' => true,
                    'status' => $code->used ? 'used' : ($code->expires_at->isPast() ? 'expired' : 'active')
                ]);
            }
            
            // Agregar códigos del historial
            foreach ($historyCodes as $code) {
                $allCodes->push((object) [
                    'id' => 'hist_' . $code->id,
                    'email' => $code->email,
                    'code' => $code->code,
                    'used' => $code->used,
                    'expires_at' => $code->expires_at,
                    'created_at' => $code->created_at,
                    'updated_at' => $code->updated_at,
                    'is_active' => false,
                    'status' => $code->status
                ]);
            }
            
            // Ordenar por fecha de creación descendente
            $allCodes = $allCodes->sortByDesc('created_at');
            
            // Paginar manualmente - 5 elementos por página
            $perPage = 5;
            $currentPage = request()->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            $items = $allCodes->slice($offset, $perPage)->values();
            
            // Crear paginador personalizado
            $invitationCodes = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $allCodes->count(),
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
        }
        
        // Log de depuración
        \Log::info('Dashboard accedido. Usuario: ' . auth()->user()->primer_nombre);
        \Log::info('Dashboard visited: ' . (session()->has('dashboard_visited') ? 'true' : 'false'));
        
        // Verificar si es la primera vez que el usuario accede al dashboard en esta sesión
        if (!session()->has('dashboard_visited')) {
            session()->put('dashboard_visited', true);
            $welcomeMessage = '¡Bienvenido de nuevo, ' . auth()->user()->primer_nombre . '!';
            \Log::info('Mensaje de bienvenida: ' . $welcomeMessage);
            return view('dashboard', compact('cursos', 'invitationCodes'))->with('welcome_message', $welcomeMessage);
        }
        
        \Log::info('Dashboard ya visitado, no mostrando mensaje');
        return view('dashboard', compact('cursos', 'invitationCodes'));
    })->name('dashboard');
    
    // Perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de Cursos
    Route::resource('cursos', CursoController::class);
    
    // Rutas de Administración
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/panel', [AdminController::class, 'index'])->name('panel');
        Route::get('/', [AdminController::class, 'index'])->name('panel'); // Alias para /admin
        Route::post('/send-invitations', [AdminController::class, 'sendInvitations'])->name('send-invitations');
        Route::delete('/delete-invitation/{invitationCode}', [AdminController::class, 'deleteInvitation'])->name('delete-invitation');
    });
});

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
