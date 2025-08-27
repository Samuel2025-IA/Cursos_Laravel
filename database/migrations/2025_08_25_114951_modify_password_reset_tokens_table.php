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
            // Agregar campos si no existen
            if (!Schema::hasColumn('password_reset_tokens', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('email');
            }
            if (!Schema::hasColumn('password_reset_tokens', 'used')) {
                $table->boolean('used')->default(false)->after('user_id');
            }
            if (!Schema::hasColumn('password_reset_tokens', 'used_at')) {
                $table->timestamp('used_at')->nullable()->after('used');
            }
            if (!Schema::hasColumn('password_reset_tokens', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('used_at');
            }
            if (!Schema::hasColumn('password_reset_tokens', 'password_hash')) {
                $table->text('password_hash')->nullable()->after('expires_at');
            }
            
            // Agregar índices si no existen
            if (!Schema::hasIndex('password_reset_tokens', 'password_reset_tokens_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasIndex('password_reset_tokens', 'password_reset_tokens_token_email_index')) {
                $table->index(['token', 'email']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // Remover campos agregados
            $table->dropForeign(['user_id']);
            $table->dropIndex(['token', 'email']);
            $table->dropColumn(['user_id', 'used', 'used_at', 'expires_at', 'password_hash']);
        });
    }
};
