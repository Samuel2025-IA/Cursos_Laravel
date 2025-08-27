<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar registros existentes donde segundo_apellido sea NULL
        DB::table('users')->whereNull('segundo_apellido')->update([
            'segundo_apellido' => 'Sin Apellido'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a NULL (aunque esto podría causar problemas si ya no es nullable)
        DB::table('users')->where('segundo_apellido', 'Sin Apellido')->update([
            'segundo_apellido' => null
        ]);
    }
};
