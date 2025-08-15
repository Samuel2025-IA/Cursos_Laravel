<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'expires_at',
        'used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean'
    ];

    /**
     * Genera un código de verificación de 8 dígitos
     */
    public static function generateCode(): string
    {
        return str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Verifica si el código es válido y no ha expirado
     */
    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    /**
     * Marca el código como usado
     */
    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }

    /**
     * Busca un código válido por email y código
     */
    public static function findValidCode(string $email, string $code): ?self
    {
        return static::where('email', $email)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    /**
     * Limpia códigos expirados
     */
    public static function cleanExpiredCodes(): void
    {
        static::where('expires_at', '<', Carbon::now())->delete();
    }
}
