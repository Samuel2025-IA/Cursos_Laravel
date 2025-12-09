<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\Curso;
use Illuminate\Auth\Events\Login;
use App\Listeners\UpdateSessionUserId;

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
        // Registrar listener para actualizar user_id en sesión después del login
        Event::listen(Login::class, UpdateSessionUserId::class);
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

        View::composer('layouts.partials.header', function ($view) {
            $data = $view->getData();

            if (isset($data['dashboardNotifications']) && isset($data['notificationsCount'])) {
                return;
            }

            if (!auth()->check()) {
                $view->with([
                    'dashboardNotifications' => collect(),
                    'notificationsCount' => 0,
                ]);
                return;
            }

            $today = Carbon::today();
            $sevenDaysAgo = Carbon::today()->subDays(7);

            // Cursos finalizados (caducados)
            $finalizedCourses = Curso::query()
                ->whereNotNull('fecha_fin')
                ->whereDate('fecha_fin', '<=', $today)
                ->orderByDesc('fecha_fin')
                ->limit(5)
                ->get();

            // Cursos nuevos (creados en los últimos 7 días)
            $newCourses = Curso::query()
                ->where('created_at', '>=', $sevenDaysAgo)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            $notifications = collect();

            // Agregar notificaciones de cursos finalizados
            $finalizedNotifications = $finalizedCourses->map(function (Curso $curso) {
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
            $newNotifications = $newCourses->map(function (Curso $curso) {
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
                ->map(function ($notification) use ($newCourses, $finalizedCourses) {
                    // Agregar timestamp para ordenamiento
                    if ($notification['tipo'] === 'nuevo') {
                        // Para cursos nuevos, usar la fecha de creación
                        $curso = $newCourses->firstWhere('id', $notification['curso_id']);
                        $notification['timestamp'] = $curso ? $curso->created_at->timestamp : 0;
                    } else {
                        // Para cursos finalizados, usar la fecha de finalización
                        $curso = $finalizedCourses->firstWhere('id', $notification['curso_id']);
                        $notification['timestamp'] = $curso && $curso->fecha_fin ? $curso->fecha_fin->timestamp : 0;
                    }
                    return $notification;
                })
                ->sortByDesc('timestamp')
                ->take(10) // Limitar a 10 notificaciones
                ->values();

            $view->with([
                'dashboardNotifications' => $notifications,
                'notificationsCount' => $notifications->count(),
            ]);
        });
    }
}
