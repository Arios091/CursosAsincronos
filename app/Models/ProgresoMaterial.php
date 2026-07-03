<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresoMaterial extends Model
{
    use HasFactory;

    protected $table = 'progreso_materiales';

    protected $fillable = [
        'user_id',
        'material_id',
        'completado',
    ];

    protected $casts = [
        'completado' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
