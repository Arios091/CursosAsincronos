<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\Modulo;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition()
    {
        return [
            'modulo_id' => Modulo::factory(),
            'titulo' => $this->faker->sentence,
            'tipo' => 'video',
            'url' => $this->faker->url,
            'orden' => 1,
        ];
    }
}
