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
        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('archivo'); // Ruta del archivo
            $table->string('tipo_archivo'); // pdf, doc, docx
            $table->string('tamaño')->nullable(); // Tamaño del archivo en bytes
            $table->string('mime_type')->nullable(); // Tipo MIME del archivo
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que subió el archivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recursos');
    }
};
