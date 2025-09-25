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
        Schema::create('invitation_codes_history', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('code', 8);
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps(); // Esto crea created_at y updated_at automáticamente
            $table->timestamp('deleted_at')->nullable(); // Cuándo fue eliminado de la tabla principal
            $table->string('status')->default('deleted'); // deleted, used, expired
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_codes_history');
    }
};
