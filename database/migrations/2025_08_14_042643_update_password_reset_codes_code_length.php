<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        Schema::table('password_reset_codes', function (Blueprint $table) {
            $table->string('code', 8)->change(); // Cambiar de 6 a 8 dígitos
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::table('password_reset_codes', function (Blueprint $table) {
            $table->string('code', 6)->change(); // Volver a 6 dígitos
        });
    }
};
