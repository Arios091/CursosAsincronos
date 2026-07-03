<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresoCurso extends Model
{
    use HasFactory;

    protected $table = 'progreso_cursos';

    protected $fillable = [
        'user_id',
        'curso_id',
        'completado',
        'progreso',
    ];

    protected $casts = [
        'completado' => 'boolean',
        'progreso' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
