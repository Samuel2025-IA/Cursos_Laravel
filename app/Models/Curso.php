<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
        'fecha_inicio',
        'fecha_fin',
        'duracion_horas',
        'instructor',
        'lugar',
        'costo',
        'form_fields',
        'has_form'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'costo' => 'decimal:2',
        'form_fields' => 'array',
        'has_form' => 'boolean'
    ];

    protected $appends = ['esta_finalizado', 'estado'];

    public function getEstaFinalizadoAttribute(): bool
    {
        if (!$this->fecha_fin) {
            return false;
        }

        return $this->fecha_fin->copy()->endOfDay()->isPast();
    }

    public function getEstadoAttribute(): string
    {
        if ($this->esta_finalizado) {
            return 'Finalizado';
        }

        return $this->activo ? 'Activo' : 'Inactivo';
    }

    public function respuestas()
    {
        return $this->hasMany(CursoRespuesta::class);
    }
}
