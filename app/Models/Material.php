<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales';

    protected $fillable = [
        'modulo_id',
        'titulo',
        'tipo',
        'url',
        'archivo',
        'duracion',
        'orden',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function opciones()
    {
        return $this->hasMany(OpcionesCuestionario::class);
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoMaterial::class);
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->esYouTube()) {
            preg_match('/(?:youtube\.com\/(?:shorts\/|live\/|[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s ]{11})/i', $this->url, $matches);
            if (isset($matches[1])) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }

        if ($this->esVimeo()) {
            preg_match('/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/i', $this->url, $matches);
            if (isset($matches[1])) {
                return 'https://player.vimeo.com/video/' . $matches[1];
            }
        }

        if ($this->esGoogleDrive()) {
            preg_match('/\/file\/d\/([^\/?#]+)/', $this->url, $matches);
            if (isset($matches[1])) {
                return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
            }
        }

        return null;
    }

    public function getYouTubeId(): ?string
    {
        preg_match('/(?:youtube\.com\/(?:shorts\/|live\/|[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s ]{11})/i', $this->url ?? '', $matches);
        return $matches[1] ?? null;
    }

    public function esYouTube(): bool
    {
        return $this->getYouTubeId() !== null;
    }

    public function esVimeo(): bool
    {
        return (bool) preg_match('/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/i', $this->url ?? '');
    }

    public function esGoogleDrive(): bool
    {
        return (bool) preg_match('/\/file\/d\/([^\/?#]+)/', $this->url ?? '');
    }
}
