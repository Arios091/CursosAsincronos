<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreguntaExamenFinal extends Model
{
    use HasFactory;

    protected $table = 'preguntas_examen_final';

    protected $fillable = [
        'examen_final_id',
        'texto',
        'orden',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    public function examenFinal()
    {
        return $this->belongsTo(ExamenFinal::class, 'examen_final_id');
    }

    public function opciones()
    {
        return $this->hasMany(OpcionExamenFinal::class, 'pregunta_id');
    }
}
