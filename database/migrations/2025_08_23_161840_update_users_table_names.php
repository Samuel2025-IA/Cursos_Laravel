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
        Schema::table('users', function (Blueprint $table) {
            // Agregar nuevos campos de nombres
            $table->string('primer_nombre')->after('id');
            $table->string('segundo_nombre')->nullable()->after('primer_nombre');
            $table->string('primer_apellido')->after('segundo_nombre');
            $table->string('segundo_apellido')->after('primer_apellido');
            
            // Eliminar campos antiguos
            $table->dropColumn(['name', 'apellido']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restaurar campos antiguos
            $table->string('name')->after('id');
            $table->string('apellido')->after('name');
            
            // Eliminar nuevos campos
            $table->dropColumn(['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido']);
        });
    }
};
