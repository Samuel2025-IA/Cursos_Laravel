<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InvitationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'used',
        'expires_at',
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Generate a unique invitation code
     */
    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Create a new invitation code for an email
     */
    public static function createForEmail(string $email): self
    {
        // Delete any existing unused codes for this email
        self::where('email', $email)->where('used', false)->delete();

        return self::create([
            'email' => $email,
            'code' => self::generateCode(),
            'expires_at' => now()->addDays(7), // Expires in 7 days
        ]);
    }

    /**
     * Check if the code is valid and not expired
     */
    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    /**
     * Mark the code as used
     */
    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }

    /**
     * Scope for valid codes
     */
    public function scopeValid($query)
    {
        return $query->where('used', false)
                    ->where('expires_at', '>', now());
    }
}


