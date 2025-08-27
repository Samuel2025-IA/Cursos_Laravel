<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Personalizar el email de recuperación de contraseña
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($notifiable->email));
            
            $data = [
                'resetUrl' => $resetUrl,
                'expiresAt' => Carbon::now()->addMinutes(60)->format('H:i'),
                'userName' => $notifiable->primer_nombre ?? $notifiable->name ?? 'Usuario'
            ];

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Recuperación de Contraseña - Diócesis de Apartadó')
                ->view('emails.password-reset-link', $data);
        });
    }
}
