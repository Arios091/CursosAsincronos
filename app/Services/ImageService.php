<?php

namespace App\Services;

use Intervention\Image\ImageManagerStatic as Image;
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

        $image = Image::make($file)->encode('webp', 85);

        if ($mode === 'fit') {
            $image->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            })->background($bgColor);
        } elseif ($mode === 'cover') {
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->crop($width, $height);
        }

        Storage::disk($this->disk)->put($folder . '/' . $filename, (string) $image);

        return $folder . '/' . $filename;
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
        $filename = 'favicon.ico';
        $image = Image::make($file)->resize(self::FAVICON_SIZE, self::FAVICON_SIZE)->encode('ico');
        Storage::disk($this->disk)->put('site/' . $filename, (string) $image);
        return 'site/' . $filename;
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
        return Storage::disk($this->disk)->delete($path);
    }

    public function url(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }
}