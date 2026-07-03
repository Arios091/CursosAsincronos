<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\ExamenFinal;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamenFinalFactory extends Factory
{
    protected $model = ExamenFinal::class;

    public function definition()
    {
        return [
            'curso_id' => Curso::factory(),
            'titulo' => 'Examen Final',
            'min_aprobacion' => 80,
        ];
    }
}
