<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar campos de documento después de segundo_apellido
            $table->enum('tipo_documento', ['CC', 'CE', 'TI', 'PP', 'NIT'])->nullable()->after('segundo_apellido');
            $table->string('numero_documento', 20)->nullable()->after('tipo_documento');
        });

        // Actualizar usuarios existentes con valores por defecto
        DB::table('users')->whereNull('tipo_documento')->update([
            'tipo_documento' => 'CC',
            'numero_documento' => DB::raw('CONCAT("TEMP_", id)')
        ]);

        // Ahora hacer los campos obligatorios y únicos
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipo_documento', ['CC', 'CE', 'TI', 'PP', 'NIT'])->nullable(false)->change();
            $table->string('numero_documento', 20)->nullable(false)->unique()->change();
            
            // Agregar índices para mejorar el rendimiento
            $table->index(['tipo_documento', 'numero_documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar índices
            $table->dropIndex(['tipo_documento', 'numero_documento']);
            
            // Eliminar campos de documento
            $table->dropColumn(['tipo_documento', 'numero_documento']);
        });
    }
};
