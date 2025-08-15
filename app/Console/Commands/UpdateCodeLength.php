<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateCodeLength extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:update-code-length';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza la longitud del campo code a 8 dígitos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('password_reset_codes')) {
            $this->error('La tabla password_reset_codes no existe.');
            return 1;
        }

        try {
            // Cambiar la longitud del campo code de 6 a 8
            DB::statement('ALTER TABLE password_reset_codes MODIFY COLUMN code VARCHAR(8) NOT NULL');
            
            $this->info('✅ Campo code actualizado exitosamente a 8 dígitos.');
            
            // Verificar el cambio
            $columns = DB::select("SHOW COLUMNS FROM password_reset_codes LIKE 'code'");
            if (!empty($columns)) {
                $this->info("📊 Campo code ahora es: " . $columns[0]->Type);
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error al actualizar la tabla:');
            $this->error('   ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
