<?php

namespace Database\Factories;

use App\Models\Cuestionario;
use App\Models\Modulo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CuestionarioFactory extends Factory
{
    protected $model = Cuestionario::class;

    public function definition()
    {
        return [
            'modulo_id' => Modulo::factory(),
            'titulo' => 'Cuestionario',
            'min_aprobacion' => 100,
        ];
    }
}
