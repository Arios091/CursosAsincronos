<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuloFactory extends Factory
{
    protected $model = Modulo::class;

    public function definition()
    {
        return [
            'curso_id' => Curso::factory(),
            'titulo' => $this->faker->sentence,
            'descripcion' => $this->faker->paragraph,
            'orden' => 1,
        ];
    }
}
