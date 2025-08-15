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
        Schema::create('password_reset_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index(); // Email del usuario
            $table->string('code', 6); // Código de 6 dígitos
            $table->timestamp('expires_at'); // Fecha de expiración
            $table->boolean('used')->default(false); // Si ya fue usado
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_codes');
    }
};
