<?php

use App\Http\Controllers\ProfileController;
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

// RUTA DE PRUEBA PARA VERIFICAR CORREO - ELIMINAR DESPUÉS
Route::get('/test-email', function () {
    try {
        // Enviar un email de prueba
        \Illuminate\Support\Facades\Mail::raw('Este es un email de prueba del sistema de recuperación de contraseña.', function($message) {
            $message->to('samuel2020zm@gmail.com')
                    ->subject('Prueba de Correo - Diócesis de Apartadó');
        });
        
        return '✅ Email de prueba enviado exitosamente. Revisa tu bandeja de entrada.';
    } catch (\Exception $e) {
        return '❌ Error al enviar email: ' . $e->getMessage();
    }
})->name('test.email');

// RUTA DE PRUEBA PARA VERIFICAR RECUPERACIÓN DE CONTRASEÑA - ELIMINAR DESPUÉS
Route::get('/test-password-reset', function () {
    try {
        // Simular el proceso de recuperación de contraseña
        $email = 'samuel2020zm@gmail.com';
        
        // Verificar si el usuario existe
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            return '❌ Usuario no encontrado con email: ' . $email;
        }
        
        // Generar token de recuperación
        $token = \Illuminate\Support\Facades\Password::createToken($user);
        
        // Crear URL de reset CORRECTA con email como query parameter
        $resetUrl = url("/reset-password/{$token}?email=" . urlencode($email));
        
        return [
            'status' => 'success',
            'message' => 'Token de recuperación generado exitosamente',
            'user_id' => $user->id,
            'email' => $email,
            'token' => $token,
            'reset_url' => $resetUrl,
            'reset_url_decoded' => urldecode($resetUrl),
            'table_exists' => \Illuminate\Support\Facades\Schema::hasTable('password_reset_tokens'),
            'table_structure' => \Illuminate\Support\Facades\DB::select('DESCRIBE password_reset_tokens'),
            'tokens_in_table' => \Illuminate\Support\Facades\DB::table('password_reset_tokens')->get()
        ];
        
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
})->name('test.password.reset');

// RUTA DE PRUEBA PARA VERIFICAR TOKEN ESPECÍFICO - ELIMINAR DESPUÉS
Route::get('/test-token/{token}', function ($token) {
    try {
        $email = request('email');
        
        if (!$email) {
            return '❌ Email no proporcionado en query parameter';
        }
        
        // Buscar el token en la base de datos
        $resetToken = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('email', $email)
            ->first();
            
        return [
            'token_provided' => $token,
            'email_provided' => $email,
            'token_found' => $resetToken ? true : false,
            'token_data' => $resetToken,
            'all_tokens' => \Illuminate\Support\Facades\DB::table('password_reset_tokens')->get()
        ];
        
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
})->name('test.token');

// RUTA DE PRUEBA PARA DIAGNOSTICAR REGISTRO - ELIMINAR DESPUÉS
Route::get('/test-register-debug', function () {
    \Log::info('=== PRUEBA DE REGISTRO DEBUG ===');
    
    try {
        // Intentar crear un usuario de prueba
        $testEmail = 'test' . time() . '@example.com';
        \Log::info('Intentando crear usuario con email: ' . $testEmail);
        
        $user = \App\Models\User::create([
            'primer_nombre' => 'Test',
            'primer_apellido' => 'User',
            'segundo_apellido' => 'Test',
            'entidad' => 'diocesis_apartado',
            'email' => $testEmail,
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        
        \Log::info('✅ Usuario creado exitosamente con ID: ' . $user->id);
        
        // Intentar autenticar
        \Illuminate\Support\Facades\Auth::login($user);
        \Log::info('✅ Usuario autenticado: ' . \Illuminate\Support\Facades\Auth::check());
        
        // Intentar redirección
        $response = redirect()->route('dashboard')
                       ->with('flash_message', '¡Prueba de debug exitosa!')
                       ->with('flash_token', 'debug-' . time());
        
        \Log::info('✅ Respuesta creada: ' . get_class($response));
        \Log::info('✅ URL destino: ' . $response->getTargetUrl());
        
        // Eliminar usuario de prueba
        $user->delete();
        \Log::info('✅ Usuario de prueba eliminado');
        
        return $response;
        
    } catch (\Exception $e) {
        \Log::error('❌ Error en prueba de debug: ' . $e->getMessage());
        \Log::error('❌ Stack trace: ' . $e->getTraceAsString());
        return 'Error: ' . $e->getMessage();
    }
})->name('test.register.debug');

// RUTA DE PRUEBA TEMPORAL - ELIMINAR DESPUÉS
Route::get('/test-dashboard', function () {
    // Crear un usuario de prueba
    $user = \App\Models\User::create([
        'primer_nombre' => 'Test',
        'primer_apellido' => 'User',
        'segundo_apellido' => 'Test',
        'entidad' => 'diocesis_apartado',
        'email' => 'test' . time() . '@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
    ]);
    
    // Autenticar al usuario
    \Illuminate\Support\Facades\Auth::login($user);
    
    // Redirigir al dashboard con mensaje flash
    return redirect()->route('dashboard')
                   ->with('flash_message', '¡Prueba de dashboard exitosa!')
                   ->with('flash_token', 'test-' . time())
                   ->with('success', true);
})->name('test.dashboard');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Con Autenticación)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
