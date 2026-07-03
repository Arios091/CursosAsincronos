<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionExamenFinal extends Model
{
    use HasFactory;

    protected $table = 'opciones_examen_final';

    protected $fillable = [
        'pregunta_id',
        'texto',
        'es_correcta',
        'orden',
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
        'orden' => 'integer',
    ];

    public function pregunta()
    {
        return $this->belongsTo(PreguntaExamenFinal::class, 'pregunta_id');
    }
}
