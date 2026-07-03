<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'estado',
        'user_id',
        'audiencia',
        'horas',
    ];

    protected $casts = [
        'audiencia' => 'string',
        'horas' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }

    public function materiales()
    {
        return $this->hasManyThrough(Material::class, Modulo::class);
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoCurso::class);
    }

    public function usuariosEnProgreso()
    {
        return $this->belongsToMany(User::class, 'progreso_cursos');
    }

    public function examenFinal()
    {
        return $this->hasOne(ExamenFinal::class);
    }
}
