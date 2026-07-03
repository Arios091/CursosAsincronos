<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\ProgresoCurso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgresoCursoFactory extends Factory
{
    protected $model = ProgresoCurso::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'curso_id' => Curso::factory(),
            'completado' => false,
            'progreso' => 0,
        ];
    }
}
