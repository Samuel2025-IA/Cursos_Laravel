<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PasswordResetCodeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Rutas para recuperación de contraseña con códigos
    Route::get('forgot-password', [PasswordResetCodeController::class, 'showRequestForm'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetCodeController::class, 'sendCode'])
        ->name('password.code.send');

    Route::get('enter-code', [PasswordResetCodeController::class, 'showCodeForm'])
        ->name('password.code.form');

    Route::post('verify-code', [PasswordResetCodeController::class, 'verifyCode'])
        ->name('password.code.verify');

    Route::get('reset-password-with-code', [PasswordResetCodeController::class, 'showResetForm'])
        ->name('password.reset.with-code');

    Route::post('reset-password-with-code', [PasswordResetCodeController::class, 'resetPassword'])
        ->name('password.reset.with-code');

    // Rutas originales de Laravel (mantenidas por compatibilidad)
    Route::get('forgot-password-old', [PasswordResetLinkController::class, 'create'])
        ->name('password.request.old');

    Route::post('forgot-password-old', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
