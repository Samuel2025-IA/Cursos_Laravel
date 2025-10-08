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
        Schema::table('cursos', function (Blueprint $table) {
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->integer('duracion_horas')->nullable();
            $table->string('instructor')->nullable();
            $table->string('lugar')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn([
                'nombre',
                'descripcion',
                'activo',
                'fecha_inicio',
                'fecha_fin',
                'duracion_horas',
                'instructor',
                'lugar',
                'costo'
            ]);
        });
    }
};
