<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'email',
        'user_id',
        'used',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'used' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verificar si el token ha expirado
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        
        return now()->isAfter($this->expires_at);
    }

    /**
     * Verificar si el token ya fue usado
     */
    public function isUsed(): bool
    {
        return $this->used;
    }

    /**
     * Marcar el token como usado
     */
    public function markAsUsed(): void
    {
        $this->update([
            'used' => true,
            'used_at' => now(),
        ]);
    }

    /**
     * Verificar si el token es válido
     */
    public function isValid(): bool
    {
        return !$this->isUsed() && !$this->isExpired();
    }
}
