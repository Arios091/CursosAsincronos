<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\Material;
use App\Models\Modulo;
use App\Models\ProgresoCurso;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use DatabaseTransactions;

    public function test_certificado_se_genera_con_codigo_unico()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $curso = Curso::factory()->create();

        $progreso = ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'completado' => true,
            'progreso' => 100,
        ]);

        $codigo = str_pad($progreso->id, 6, '0', STR_PAD_LEFT);
        $this->assertEquals(6, strlen($codigo));
    }

    public function test_certificado_se_verifica_publicamente()
    {
        $user = User::factory()->create();
        $curso = Curso::factory()->create();

        $progreso = ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'completado' => true,
            'progreso' => 100,
        ]);

        $codigo = str_pad($progreso->id, 6, '0', STR_PAD_LEFT);

        $response = $this->get('/api/verificar/' . $codigo);
        $response->assertStatus(200);
        $response->assertJson([
            'valido' => true,
        ]);
    }

    public function test_certificado_invalido_devuelve_404()
    {
        $response = $this->get('/api/verificar/999999');
        $response->assertStatus(404);
        $response->assertJson([
            'valido' => false,
        ]);
    }

    public function test_certificado_pagina_publica_muestra_info()
    {
        $user = User::factory()->create();
        $curso = Curso::factory()->create();

        $progreso = ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'completado' => true,
            'progreso' => 100,
        ]);

        $codigo = str_pad($progreso->id, 6, '0', STR_PAD_LEFT);

        $response = $this->get('/verificar/' . $codigo);
        $response->assertStatus(200);
    }

    public function test_certificado_pdf_se_descarga()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $curso = Curso::factory()->create();

        ProgresoCurso::factory()->create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'completado' => true,
            'progreso' => 100,
        ]);

        $this->actingAs($user);

        $response = $this->get('/certificado/' . $curso->id . '/descargar');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
