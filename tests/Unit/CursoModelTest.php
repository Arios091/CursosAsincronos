<?php

namespace Tests\Unit;

use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Material;
use App\Models\OpcionesCuestionario;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CursoModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_curso_tiene_modulos()
    {
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $this->assertTrue($curso->modulos->contains($modulo));
    }

    public function test_curso_tiene_materiales_a_traves_de_modulos()
    {
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $material = Material::factory()->create(['modulo_id' => $modulo->id]);
        $this->assertTrue($curso->materiales->contains($material));
    }

    public function test_curso_tiene_cuestionarios()
    {
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $material = Material::factory()->create([
            'modulo_id' => $modulo->id,
            'tipo' => 'cuestionario',
        ]);
        $opcion = OpcionesCuestionario::factory()->create([
            'material_id' => $material->id,
        ]);
        $this->assertEquals('cuestionario', $material->tipo);
        $this->assertTrue($material->opciones->contains($opcion));
    }

    public function test_curso_tiene_audiencia_por_defecto()
    {
        $curso = Curso::factory()->create();
        $this->assertEquals('docente', $curso->audiencia);
    }

    public function test_curso_tiene_estado_por_defecto()
    {
        $curso = Curso::factory()->create();
        $this->assertEquals('publicado', $curso->estado);
    }
}
