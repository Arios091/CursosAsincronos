<?php

namespace Database\Factories;

use App\Models\OpcionPregunta;
use App\Models\PreguntaCuestionario;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpcionPreguntaFactory extends Factory
{
    protected $model = OpcionPregunta::class;

    public function definition()
    {
        return [
            'pregunta_id' => PreguntaCuestionario::factory(),
            'texto' => $this->faker->sentence,
            'es_correcta' => false,
            'orden' => 1,
        ];
    }
}
