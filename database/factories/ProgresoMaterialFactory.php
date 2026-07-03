<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\ProgresoMaterial;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgresoMaterialFactory extends Factory
{
    protected $model = ProgresoMaterial::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'material_id' => Material::factory(),
            'completado' => false,
        ];
    }
}
