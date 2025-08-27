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
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // Primero eliminar la clave primaria existente en email
            $table->dropPrimary();
            
            // Agregar una clave primaria auto-incremental en id
            $table->id()->first();
            
            // Hacer que email no sea único (permitir múltiples tokens por usuario)
            $table->string('email')->change();
            
            // Agregar índices para mejorar el rendimiento
            $table->index(['email', 'token']);
            $table->index(['token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // Revertir cambios
            $table->dropIndex(['email', 'token']);
            $table->dropIndex(['token']);
            
            // Eliminar la columna id
            $table->dropColumn('id');
            
            // Restaurar email como clave primaria
            $table->primary('email');
        });
    }
};
