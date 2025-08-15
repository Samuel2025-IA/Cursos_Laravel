<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreatePasswordResetCodesTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:create-password-reset-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea la tabla password_reset_codes para el sistema de recuperación de contraseña';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (Schema::hasTable('password_reset_codes')) {
            $this->error('La tabla password_reset_codes ya existe.');
            return 1;
        }

        Schema::create('password_reset_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index(); // Email del usuario
            $table->string('code', 6); // Código de 6 dígitos
            $table->timestamp('expires_at'); // Fecha de expiración
            $table->boolean('used')->default(false); // Si ya fue usado
            $table->timestamps();
        });

        $this->info('Tabla password_reset_codes creada exitosamente.');
        return 0;
    }
}
