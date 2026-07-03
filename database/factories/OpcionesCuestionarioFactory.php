<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\OpcionesCuestionario;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpcionesCuestionarioFactory extends Factory
{
    protected $model = OpcionesCuestionario::class;

    public function definition()
    {
        return [
            'material_id' => Material::factory(),
            'texto' => $this->faker->sentence,
            'es_correcta' => false,
            'orden' => 1,
        ];
    }
}
