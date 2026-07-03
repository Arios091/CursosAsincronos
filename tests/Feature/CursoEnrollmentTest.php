<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\User;
use App\Models\ProgresoCurso;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CursoEnrollmentTest extends TestCase
{
    use DatabaseTransactions;

    public function test_usuario_puede_inscribirse_en_curso()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'curso_en_progreso_id' => null,
        ]);

        $curso = Curso::factory()->create();

        $response = $this->actingAs($user)->post('/cursos/' . $curso->id . '/comenzar');

        $response->assertRedirect('/mis-cursos/' . $curso->id);
        $this->assertDatabaseHas('progreso_cursos', [
            'user_id' => $user->id,
            'curso_id' => $curso->id,
        ]);
        $this->assertNotNull($user->fresh()->curso_en_progreso_id);
    }

    public function test_usuario_no_puede_inscribirse_en_dos_cursos()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'curso_en_progreso_id' => null,
        ]);

        $curso1 = Curso::factory()->create();
        $curso2 = Curso::factory()->create();

        $this->actingAs($user)->post('/cursos/' . $curso1->id . '/comenzar');

        $response = $this->actingAs($user)->post('/cursos/' . $curso2->id . '/comenzar');
        $response->assertSessionHas('error');
    }

    public function test_usuario_puede_reabrir_curso_ya_inscrito()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'curso_en_progreso_id' => null,
        ]);

        $curso = Curso::factory()->create();

        $this->actingAs($user)->post('/cursos/' . $curso->id . '/comenzar');

        $response = $this->actingAs($user)->post('/cursos/' . $curso->id . '/comenzar');
        $response->assertRedirect('/mis-cursos/' . $curso->id);
    }

    public function test_progreso_curso_se_actualiza()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $curso = Curso::factory()->create();
        $progreso = ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'progreso' => 50,
        ]);

        $progreso->update(['progreso' => 75]);
        $this->assertEquals(75, $progreso->fresh()->progreso);
    }
}
