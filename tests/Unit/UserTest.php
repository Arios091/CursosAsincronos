<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Curso;
use App\Models\ProgresoCurso;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_usuario_admin_global_tiene_role_admin_global()
    {
        $user = User::factory()->create(['role' => 'admin_global']);
        $this->assertTrue($user->isAdminGlobal());
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->puedeGestionarUsuarios());
        $this->assertTrue($user->puedeGestionarCursos());
    }

    public function test_usuario_admin_es_admin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isAdminGlobal());
        $this->assertFalse($user->puedeGestionarUsuarios());
        $this->assertTrue($user->puedeGestionarCursos());
    }

    public function test_usuario_docente_no_es_admin()
    {
        $user = User::factory()->create(['role' => 'docente']);
        $this->assertTrue($user->isDocente());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->puedeGestionarUsuarios());
        $this->assertFalse($user->puedeGestionarCursos());
    }

    public function test_usuario_estudiante_tiene_role_correcto()
    {
        $user = User::factory()->create(['role' => 'estudiante']);
        $this->assertTrue($user->isEstudiante());
        $this->assertFalse($user->isAdmin());
    }

    public function test_usuario_tiene_relacion_curso_en_progreso()
    {
        $curso = Curso::factory()->create();
        $user = User::factory()->create(['curso_en_progreso_id' => $curso->id]);
        $this->assertNotNull($user->cursoEnProgreso);
        $this->assertEquals($curso->id, $user->cursoEnProgreso->id);
    }

    public function test_usuario_tiene_relacion_progresos()
    {
        $user = User::factory()->create();
        $curso = Curso::factory()->create();
        $progreso = ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
        ]);
        $this->assertTrue($user->progresos->contains($progreso));
    }
}
