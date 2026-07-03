<?php

namespace Database\Factories;

use App\Models\ExamenFinal;
use App\Models\PreguntaExamenFinal;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreguntaExamenFinalFactory extends Factory
{
    protected $model = PreguntaExamenFinal::class;

    public function definition()
    {
        return [
            'examen_final_id' => ExamenFinal::factory(),
            'texto' => $this->faker->sentence,
            'orden' => 1,
        ];
    }
}
