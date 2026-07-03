<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\ResultadoCuestionario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultadoCuestionarioFactory extends Factory
{
    protected $model = ResultadoCuestionario::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'material_id' => Material::factory(),
            'intentos' => 1,
            'puntaje' => 0,
            'aprobado' => false,
        ];
    }
}
