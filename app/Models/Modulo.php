<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'modulos';

    protected $fillable = [
        'curso_id',
        'titulo',
        'descripcion',
        'orden',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function materiales()
    {
        return $this->hasMany(Material::class);
    }

    public function cuestionario()
    {
        return $this->hasOne(Cuestionario::class);
    }
}
