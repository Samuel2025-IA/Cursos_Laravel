<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearWelcomeSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'welcome:clear-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all welcome message sessions to force showing alerts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Limpiando sesiones de bienvenida...');
        
        // Limpiar cache de sesiones
        Cache::flush();
        
        // Limpiar archivos de sesión si usamos file driver
        $sessionPath = storage_path('framework/sessions');
        if (is_dir($sessionPath)) {
            $files = glob($sessionPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            $this->info('Archivos de sesión eliminados');
        }
        
        $this->info('✅ Sesiones de bienvenida limpiadas');
        $this->info('Ahora las alertas de bienvenida se mostrarán en la próxima visita');
        
        return 0;
    }
}