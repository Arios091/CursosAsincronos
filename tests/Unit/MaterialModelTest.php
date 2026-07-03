<?php

namespace Tests\Unit;

use App\Models\Material;
use App\Models\Modulo;
use App\Models\Curso;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MaterialModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_material_video_youtube_detecta_plataforma()
    {
        $material = Material::factory()->create([
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'tipo' => 'video',
        ]);
        $this->assertTrue($material->esYouTube());
        $this->assertFalse($material->esVimeo());
        $this->assertFalse($material->esGoogleDrive());
    }

    public function test_material_video_youtube_embed_url()
    {
        $material = Material::factory()->create([
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'tipo' => 'video',
        ]);
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ', $material->getEmbedUrlAttribute());
    }

    public function test_material_video_youtube_shorts()
    {
        $material = Material::factory()->create([
            'url' => 'https://youtube.com/shorts/abc123def45',
            'tipo' => 'video',
        ]);
        $this->assertTrue($material->esYouTube());
        $this->assertStringContainsString('embed', $material->getEmbedUrlAttribute());
    }

    public function test_material_video_youtu_be()
    {
        $material = Material::factory()->create([
            'url' => 'https://youtu.be/dQw4w9WgXcQ',
            'tipo' => 'video',
        ]);
        $this->assertTrue($material->esYouTube());
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ', $material->getEmbedUrlAttribute());
    }

    public function test_material_video_vimeo()
    {
        $material = Material::factory()->create([
            'url' => 'https://vimeo.com/123456789',
            'tipo' => 'video',
        ]);
        $this->assertTrue($material->esVimeo());
        $this->assertFalse($material->esYouTube());
        $this->assertEquals('https://player.vimeo.com/video/123456789', $material->getEmbedUrlAttribute());
    }

    public function test_material_video_google_drive()
    {
        $material = Material::factory()->create([
            'url' => 'https://drive.google.com/file/d/abc123def456/view',
            'tipo' => 'video',
        ]);
        $this->assertTrue($material->esGoogleDrive());
        $this->assertEquals('https://drive.google.com/file/d/abc123def456/preview', $material->getEmbedUrlAttribute());
    }

    public function test_material_pdf_no_tiene_embed()
    {
        $material = Material::factory()->create([
            'url' => 'https://example.com/doc.pdf',
            'tipo' => 'pdf',
        ]);
        $this->assertFalse($material->esYouTube());
        $this->assertFalse($material->esVimeo());
        $this->assertFalse($material->esGoogleDrive());
        $this->assertNull($material->getEmbedUrlAttribute());
    }

    public function test_material_cuestionario_tiene_opciones()
    {
        $material = Material::factory()->create([
            'tipo' => 'cuestionario',
        ]);
        $this->assertEquals('cuestionario', $material->tipo);
    }
}
