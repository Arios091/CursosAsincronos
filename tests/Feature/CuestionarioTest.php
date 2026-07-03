<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\Cuestionario;
use App\Models\Modulo;
use App\Models\PreguntaCuestionario;
use App\Models\OpcionPregunta;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CuestionarioTest extends TestCase
{
    use DatabaseTransactions;

    public function test_cuestionario_aprueba_con_puntaje_suficiente()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $cuestionario = Cuestionario::factory()->create([
            'modulo_id' => $modulo->id,
            'min_aprobacion' => 100,
        ]);

        $pregunta = PreguntaCuestionario::factory()->create([
            'cuestionario_id' => $cuestionario->id,
            'texto' => '2+2=?',
        ]);
        $correcta = OpcionPregunta::factory()->create([
            'pregunta_id' => $pregunta->id,
            'texto' => '4',
            'es_correcta' => true,
        ]);
        OpcionPregunta::factory()->create([
            'pregunta_id' => $pregunta->id,
            'texto' => '5',
            'es_correcta' => false,
        ]);

        $this->actingAs($user);

        $response = $this->post('/cursos/modulo/' . $modulo->id . '/cuestionario', [
            'respuestas' => [$pregunta->id => $correcta->id],
        ]);

        $response->assertJson([
            'aprobado' => true,
        ]);
    }

    public function test_cuestionario_reprueba_con_puntaje_insuficiente()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $cuestionario = Cuestionario::factory()->create([
            'modulo_id' => $modulo->id,
            'min_aprobacion' => 100,
        ]);

        $pregunta = PreguntaCuestionario::factory()->create([
            'cuestionario_id' => $cuestionario->id,
        ]);
        $incorrecta = OpcionPregunta::factory()->create([
            'pregunta_id' => $pregunta->id,
            'es_correcta' => false,
        ]);

        $this->actingAs($user);

        $response = $this->post('/cursos/modulo/' . $modulo->id . '/cuestionario', [
            'respuestas' => [$pregunta->id => $incorrecta->id],
        ]);

        $response->assertJson([
            'aprobado' => false,
        ]);
    }

    public function test_cuestionario_usa_min_aprobacion()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $cuestionario = Cuestionario::factory()->create([
            'modulo_id' => $modulo->id,
            'min_aprobacion' => 50,
        ]);

        $pregunta = PreguntaCuestionario::factory()->create([
            'cuestionario_id' => $cuestionario->id,
        ]);
        $correcta = OpcionPregunta::factory()->create([
            'pregunta_id' => $pregunta->id,
            'es_correcta' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post('/cursos/modulo/' . $modulo->id . '/cuestionario', [
            'respuestas' => [$pregunta->id => $correcta->id],
        ]);

        $response->assertJson([
            'aprobado' => true,
        ]);
    }

    public function test_cuestionario_incrementa_intentos()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $curso = Curso::factory()->create();
        $modulo = Modulo::factory()->create(['curso_id' => $curso->id]);
        $cuestionario = Cuestionario::factory()->create([
            'modulo_id' => $modulo->id,
        ]);

        $pregunta = PreguntaCuestionario::factory()->create([
            'cuestionario_id' => $cuestionario->id,
        ]);
        $correcta = OpcionPregunta::factory()->create([
            'pregunta_id' => $pregunta->id,
            'es_correcta' => true,
        ]);

        $this->actingAs($user);

        $this->post('/cursos/modulo/' . $modulo->id . '/cuestionario', [
            'respuestas' => [$pregunta->id => $correcta->id],
        ]);

        $this->assertDatabaseHas('resultado_cuestionarios', [
            'user_id' => $user->id,
            'cuestionario_id' => $cuestionario->id,
        ]);
    }
}
