<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreguntaCuestionario extends Model
{
    use HasFactory;

    protected $table = 'preguntas_cuestionario';

    protected $fillable = [
        'cuestionario_id',
        'texto',
        'justificacion',
        'imagen',
        'orden',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class);
    }

    public function opciones()
    {
        return $this->hasMany(OpcionPregunta::class, 'pregunta_id');
    }
}
