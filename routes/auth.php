<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas para Usuarios NO Autenticados (Guest) - SIN middleware
|--------------------------------------------------------------------------
*/

// Registro de usuarios
Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);

// Inicio de sesión
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);

// Recuperación de contraseña
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

// Reset de contraseña
Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');

/*
|--------------------------------------------------------------------------
| Rutas para Usuarios Autenticados
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Confirmación de contraseña
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Actualización de contraseña
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    // Cerrar sesión
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
