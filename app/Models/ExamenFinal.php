<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenFinal extends Model
{
    use HasFactory;

    protected $table = 'examenes_finales';

    protected $fillable = [
        'curso_id',
        'titulo',
        'min_aprobacion',
    ];

    protected $casts = [
        'min_aprobacion' => 'integer',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function preguntas()
    {
        return $this->hasMany(PreguntaExamenFinal::class, 'examen_final_id');
    }

    public function resultados()
    {
        return $this->hasMany(ResultadoExamenFinal::class, 'examen_final_id');
    }
}
