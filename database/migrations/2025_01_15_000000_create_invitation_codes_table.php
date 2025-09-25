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
        Schema::create('invitation_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('code', 8)->unique();
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['code', 'used']);
            $table->index(['email', 'used']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_codes');
    }
};


