<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoExamenFinal extends Model
{
    use HasFactory;

    protected $table = 'resultados_examen_final';

    protected $fillable = [
        'user_id',
        'examen_final_id',
        'intentos',
        'puntaje',
        'aprobado',
    ];

    protected $casts = [
        'aprobado' => 'boolean',
        'intentos' => 'integer',
        'puntaje' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examenFinal()
    {
        return $this->belongsTo(ExamenFinal::class, 'examen_final_id');
    }
}
