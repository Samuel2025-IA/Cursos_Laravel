<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationCodeHistory extends Model
{
    protected $table = 'invitation_codes_history'; // Especificar el nombre correcto de la tabla
    
    protected $fillable = [
        'email',
        'code',
        'used',
        'expires_at',
        'deleted_at',
        'status',
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Create a history entry from an InvitationCode
     */
    public static function createFromInvitationCode(InvitationCode $invitationCode, string $status = 'deleted'): self
    {
        return self::create([
            'email' => $invitationCode->email,
            'code' => $invitationCode->code,
            'used' => $invitationCode->used,
            'expires_at' => $invitationCode->expires_at,
            'created_at' => $invitationCode->created_at,
            'updated_at' => $invitationCode->updated_at,
            'deleted_at' => now(),
            'status' => $status,
        ]);
    }
}
