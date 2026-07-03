<?php

namespace Database\Factories;

use App\Models\Cuestionario;
use App\Models\PreguntaCuestionario;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreguntaCuestionarioFactory extends Factory
{
    protected $model = PreguntaCuestionario::class;

    public function definition()
    {
        return [
            'cuestionario_id' => Cuestionario::factory(),
            'texto' => $this->faker->sentence,
            'orden' => 1,
        ];
    }
}
