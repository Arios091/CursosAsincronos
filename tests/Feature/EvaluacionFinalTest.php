<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\ExamenFinal;
use App\Models\Material;
use App\Models\Modulo;
use App\Models\OpcionExamenFinal;
use App\Models\PreguntaExamenFinal;
use App\Models\ProgresoCurso;
use App\Models\ResultadoExamenFinal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EvaluacionFinalTest extends TestCase
{
    use DatabaseTransactions;

    public function test_completar_material_sin_examen_final_completa_curso()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $material = Material::factory()->create(['modulo_id' => $modulo->id]);

        ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'progreso' => 0,
            'completado' => false,
        ]);

        $this->actingAs($user);
        $response = $this->post('/cursos/material/' . $material->id . '/completar');
        $response->assertStatus(200);
        $response->assertJson(['completado' => true]);

        $progresoCurso = ProgresoCurso::where('user_id', $user->id)->where('curso_id', $curso->id)->first();
        $this->assertTrue($progresoCurso->completado);
    }

    public function test_curso_se_completa_al_aprobar_examen_final()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $curso = Curso::factory()->create();

        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $material = Material::factory()->create(['modulo_id' => $modulo->id]);

        $examenFinal = ExamenFinal::factory()->create([
            'curso_id' => $curso->id,
            'min_aprobacion' => 80,
        ]);
        $pregunta = PreguntaExamenFinal::factory()->create([
            'examen_final_id' => $examenFinal->id,
        ]);
        $correcta = OpcionExamenFinal::factory()->create([
            'pregunta_id' => $pregunta->id,
            'es_correcta' => true,
        ]);
        OpcionExamenFinal::factory()->create([
            'pregunta_id' => $pregunta->id,
            'es_correcta' => false,
        ]);

        ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'progreso' => 100,
            'completado' => false,
        ]);

        $user->curso_en_progreso_id = $curso->id;
        $user->save();

        $this->actingAs($user);

        $this->post(route('cursos.examen-final', $curso), [
            'respuestas' => [$pregunta->id => $correcta->id],
        ])->assertJson(['aprobado' => true]);

        $progresoCurso = ProgresoCurso::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->first();

        $this->assertTrue($progresoCurso->completado);
        $this->assertEquals(100, $progresoCurso->progreso);
        $this->assertNull($user->fresh()->curso_en_progreso_id);
    }

    public function test_examen_final_rechaza_puntaje_menor_a_80()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $material = Material::factory()->create(['modulo_id' => $modulo->id]);

        $examenFinal = ExamenFinal::factory()->create([
            'curso_id' => $curso->id,
            'min_aprobacion' => 80,
        ]);
        $pregunta = PreguntaExamenFinal::factory()->create([
            'examen_final_id' => $examenFinal->id,
        ]);
        $incorrecta = OpcionExamenFinal::factory()->create([
            'pregunta_id' => $pregunta->id,
            'es_correcta' => false,
        ]);

        ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'progreso' => 100,
            'completado' => false,
        ]);

        $this->actingAs($user);

        $this->post(route('cursos.examen-final', $curso), [
            'respuestas' => [$pregunta->id => $incorrecta->id],
        ])->assertJson([
            'aprobado' => false,
            'puntaje' => 0,
        ]);
    }

    public function test_completar_material_libera_siguiente()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $mat1 = Material::factory()->create(['modulo_id' => $modulo->id, 'orden' => 1]);
        $mat2 = Material::factory()->create(['modulo_id' => $modulo->id, 'orden' => 2]);

        ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'progreso' => 0,
            'completado' => false,
        ]);

        $this->actingAs($user);

        $this->post('/cursos/material/' . $mat1->id . '/completar')
            ->assertJson([
                'siguiente' => ['id' => $mat2->id],
            ]);
    }
}
