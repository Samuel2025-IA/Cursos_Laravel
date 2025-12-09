<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CursoRespuesta extends Model
{
    protected $fillable = [
        'curso_id',
        'user_id',
        'respuestas',
        'puntuacion',
        'total_preguntas',
        'preguntas_correctas',
        'completado_at'
    ];

    protected $casts = [
        'respuestas' => 'array',
        'completado_at' => 'datetime',
        'puntuacion' => 'integer',
        'total_preguntas' => 'integer',
        'preguntas_correctas' => 'integer'
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
