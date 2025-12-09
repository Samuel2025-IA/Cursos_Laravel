<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UpdateSessionUserId
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        try {
            $sessionsTable = config('session.table', 'sessions');
            $sessionId = Session::getId();
            $userId = $event->user->id;
            
            // Verificar si existe la columna user_id
            $hasUserIdColumn = DB::getSchemaBuilder()->hasColumn($sessionsTable, 'user_id');
            
            if ($hasUserIdColumn && $sessionId) {
                // Intentar actualizar la sesión, con varios intentos si es necesario
                $maxAttempts = 3;
                $attempt = 0;
                $updated = false;
                
                while ($attempt < $maxAttempts && !$updated) {
                    $result = DB::table($sessionsTable)
                        ->where('id', $sessionId)
                        ->update(['user_id' => $userId]);
                    
                    if ($result > 0) {
                        $updated = true;
                    } else {
                        // Si no se actualizó, esperar un poco y reintentar
                        $attempt++;
                        if ($attempt < $maxAttempts) {
                            usleep(50000); // 50ms
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Si hay algún error, registrar pero no fallar el login
            \Log::warning('Error al guardar user_id en sesión desde listener: ' . $e->getMessage());
        }
    }
}
