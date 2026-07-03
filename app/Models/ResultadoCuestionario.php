<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoCuestionario extends Model
{
    use HasFactory;

    protected $table = 'resultado_cuestionarios';

    protected $fillable = [
        'user_id',
        'material_id',
        'cuestionario_id',
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

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'cuestionario_id');
    }
}
