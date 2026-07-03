<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionesCuestionario extends Model
{
    use HasFactory;

    protected $table = 'opciones_cuestionario';

    protected $fillable = [
        'material_id',
        'texto',
        'es_correcta',
        'orden',
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
