<?php

namespace Database\Factories;

use App\Models\OpcionExamenFinal;
use App\Models\PreguntaExamenFinal;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpcionExamenFinalFactory extends Factory
{
    protected $model = OpcionExamenFinal::class;

    public function definition()
    {
        return [
            'pregunta_id' => PreguntaExamenFinal::factory(),
            'texto' => $this->faker->sentence,
            'es_correcta' => false,
            'orden' => 1,
        ];
    }
}
