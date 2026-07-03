<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuestionario extends Model
{
    use HasFactory;

    protected $table = 'cuestionarios';

    protected $fillable = [
        'modulo_id',
        'titulo',
        'min_aprobacion',
    ];

    protected $casts = [
        'min_aprobacion' => 'integer',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function preguntas()
    {
        return $this->hasMany(PreguntaCuestionario::class);
    }

    public function resultados()
    {
        return $this->hasMany(ResultadoCuestionario::class, 'cuestionario_id');
    }
}
