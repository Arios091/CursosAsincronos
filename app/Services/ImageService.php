<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public const CURSO_WIDTH = 400;
    public const CURSO_HEIGHT = 225;

    public const HERO_WIDTH = 1920;
    public const HERO_HEIGHT = 600;

    public const FAVICON_SIZE = 32;

    public const LOGO_MAX_WIDTH = 400;
    public const LOGO_MAX_HEIGHT = 150;

    public const LOGIN_BG_WIDTH = 1920;
    public const LOGIN_BG_HEIGHT = 1080;

    protected string $disk = 'public';

    public function __construct(string $disk = 'public')
    {
        $this->disk = $disk;
    }

    public function uploadAndResize(
        UploadedFile $file,
        string $folder,
        int $width,
        int $height,
        string $mode = 'fit',
        string $bgColor = '#ffffff'
    ): string {
        $filename = Str::uuid() . '.webp';
        $tempPath = sys_get_temp_dir() . '/' . $filename;

        try {
            // Create image from uploaded file using GD
            $src = $this->createImageFromFile($file);
            if (!$src) {
                throw new \Exception('No se pudo crear imagen desde archivo. Formato no soportado.');
            }

            $srcW = imagesx($src);
            $srcH = imagesy($src);

            // Create destination image
            $dst = imagecreatetruecolor($width, $height);

            // Handle transparency for PNG/WebP
            if ($mode === 'fit') {
                // Fill with background color
                $bg = $this->hexToRgb($bgColor);
                $bgColorAllocated = imagecolorallocate($dst, $bg['r'], $bg['g'], $bg['b']);
                imagefill($dst, 0, 0, $bgColorAllocated);

                // Calculate fit dimensions maintaining aspect ratio
                $ratio = min($width / $srcW, $height / $srcH);
                $newW = (int)($srcW * $ratio);
                $newH = (int)($srcH * $ratio);
                $offsetX = (int)(($width - $newW) / 2);
                $offsetY = (int)(($height - $newH) / 2);

                imagecopyresampled($dst, $src, $offsetX, $offsetY, 0, 0, $newW, $newH, $srcW, $srcH);
            } elseif ($mode === 'cover') {
                // Crop to fill (cover)
                $ratio = max($width / $srcW, $height / $srcH);
                $newW = (int)($srcW * $ratio);
                $newH = (int)($srcH * $ratio);
                $offsetX = (int)(($width - $newW) / 2);
                $offsetY = (int)(($height - $newH) / 2);

                $tempCrop = imagecreatetruecolor($newW, $newH);
                imagecopyresampled($tempCrop, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);
                imagecopy($dst, $tempCrop, $offsetX, $offsetY, 0, 0, $width, $height);
                imagedestroy($tempCrop);
            }

            // Save as WebP (quality 85)
            imagewebp($dst, $tempPath, 85);

            // Upload to storage
            Storage::disk($this->disk)->put($folder . '/' . $filename, file_get_contents($tempPath));

            // Cleanup
            @unlink($tempPath);
            imagedestroy($src);
            imagedestroy($dst);

            return $folder . '/' . $filename;

        } catch (\Throwable $e) {
            // Cleanup on error
            @unlink($tempPath);
            if (isset($src)) imagedestroy($src);
            if (isset($dst)) imagedestroy($dst);
            
            \Log::error('ImageService uploadAndResize failed: ' . $e->getMessage(), [
                'folder' => $folder,
                'width' => $width,
                'height' => $height,
                'mode' => $mode,
            ]);
            
            // Fallback: store original without resize
            $fallbackName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            return $file->storeAs($folder, $fallbackName, $this->disk);
        }
    }

    protected function createImageFromFile(UploadedFile $file)
    {
        $path = $file->getRealPath();
        $bytes = @file_get_contents($path);
        if ($bytes === false) {
            return null;
        }

        // Detectar el tipo real de imagen por su contenido (magic bytes),
        // no confiar unicamente en el MIME reportado por el navegador/carga.
        $info = @getimagesizefromstring($bytes);
        $type = $info ? ($info[2] ?? null) : null;

        switch ($type) {
            case IMAGETYPE_JPEG:
                return @imagecreatefromstring($bytes);
            case IMAGETYPE_PNG:
                $img = @imagecreatefromstring($bytes);
                if ($img) {
                    imagealphablending($img, false);
                    imagesavealpha($img, true);
                }
                return $img;
            case IMAGETYPE_GIF:
                return @imagecreatefromstring($bytes);
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    return @imagecreatefromwebp($path);
                }
                return @imagecreatefromstring($bytes);
            default:
                // Fallback: intentar con cada loader de GD por si el tipo no fue detectado
                return $this->tryAllGdLoaders($path, $bytes);
        }
    }

    protected function tryAllGdLoaders(string $path, string $bytes)
    {
        foreach (['imagecreatefromjpeg' => $path, 'imagecreatefrompng' => $path, 'imagecreatefromgif' => $path, 'imagecreatefromstring' => $bytes] as $fn => $arg) {
            if (!function_exists($fn)) {
                continue;
            }
            $img = @$fn($arg);
            if ($img) {
                if (strpos($fn, 'png') !== false) {
                    imagealphablending($img, false);
                    imagesavealpha($img, true);
                }
                return $img;
            }
        }
        return null;
    }

    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        $r = hexdec($hex[0] . $hex[1]);
        $g = hexdec($hex[2] . $hex[3]);
        $b = hexdec($hex[4] . $hex[5]);
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    public function uploadCurso(UploadedFile $file): string
    {
        return $this->uploadAndResize(
            $file,
            'cursos',
            self::CURSO_WIDTH,
            self::CURSO_HEIGHT,
            'fit',
            '#ffffff'
        );
    }

    public function uploadHero(UploadedFile $file): string
    {
        return $this->uploadAndResize(
            $file,
            'hero',
            self::HERO_WIDTH,
            self::HERO_HEIGHT,
            'cover'
        );
    }

    public function uploadLogo(UploadedFile $file): string
    {
        return $this->uploadAndResize(
            $file,
            'site',
            self::LOGO_MAX_WIDTH,
            self::LOGO_MAX_HEIGHT,
            'fit',
            'transparent'
        );
    }

    public function uploadFavicon(UploadedFile $file): string
    {
        // For favicon, just resize to 32x32 and save as PNG (ICO support limited in GD)
        $filename = 'favicon.png';
        $tempPath = sys_get_temp_dir() . '/' . $filename;

        try {
            $src = $this->createImageFromFile($file);
            if (!$src) {
                throw new \Exception('Formato no soportado para favicon');
            }

            $dst = imagecreatetruecolor(self::FAVICON_SIZE, self::FAVICON_SIZE);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);

            $srcW = imagesx($src);
            $srcH = imagesy($src);
            $ratio = min(self::FAVICON_SIZE / $srcW, self::FAVICON_SIZE / $srcH);
            $newW = (int)($srcW * $ratio);
            $newH = (int)($srcH * $ratio);
            $offsetX = (int)((self::FAVICON_SIZE - $newW) / 2);
            $offsetY = (int)((self::FAVICON_SIZE - $newH) / 2);

            imagecopyresampled($dst, $src, $offsetX, $offsetY, 0, 0, $newW, $newH, $srcW, $srcH);
            imagepng($dst, $tempPath);

            Storage::disk($this->disk)->put('site/' . $filename, file_get_contents($tempPath));

            @unlink($tempPath);
            imagedestroy($src);
            imagedestroy($dst);

            return 'site/' . $filename;
        } catch (\Throwable $e) {
            @unlink($tempPath);
            \Log::error('ImageService uploadFavicon failed: ' . $e->getMessage());
            $fallbackName = 'favicon.' . $file->getClientOriginalExtension();
            return $file->storeAs('site', $fallbackName, $this->disk);
        }
    }

    public function uploadLoginBg(UploadedFile $file): string
    {
        return $this->uploadAndResize(
            $file,
            'site',
            self::LOGIN_BG_WIDTH,
            self::LOGIN_BG_HEIGHT,
            'cover'
        );
    }

    public function delete(string $path): bool
    {
        try {
            return Storage::disk($this->disk)->delete($path);
        } catch (\Throwable $e) {
            \Log::error('ImageService delete failed: ' . $e->getMessage(), ['path' => $path]);
            return false;
        }
    }

    public function url(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }
}