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
        Schema::create('curso_respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('respuestas'); // Almacena todas las respuestas del formulario
            $table->integer('puntuacion')->default(0); // Puntuación basada en respuestas correctas
            $table->integer('total_preguntas')->default(0); // Total de preguntas con respuesta correcta
            $table->integer('preguntas_correctas')->default(0); // Número de preguntas respondidas correctamente
            $table->timestamp('completado_at')->nullable(); // Fecha y hora de finalización
            $table->timestamps();
            
            // Índice único para evitar que un usuario complete el mismo curso múltiples veces
            $table->unique(['curso_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_respuestas');
    }
};
