<?php

namespace App\Http\Controllers;

use App\Models\PageSetting;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin_global');
    }

    public function index()
    {
        $settings = PageSetting::all()->keyBy('key');
        return view('admin.page-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'primary_color' => 'color',
            'secondary_color' => 'color',
            'progress_bar_color' => 'color',
            'hero_title' => 'text',
            'hero_subtitle' => 'text',
            'contact_phone' => 'text',
            'contact_email' => 'text',
            'contact_address' => 'text',
            'institution_name' => 'text',
            'institution_acronym' => 'text',
            'meta_description' => 'text',
            'footer_text' => 'text',
            'social_facebook' => 'text',
            'social_twitter' => 'text',
            'social_instagram' => 'text',
        ];

        foreach ($fields as $key => $type) {
            if ($request->has($key)) {
                PageSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key), 'type' => $type]
                );
            }
        }

        $imageFields = ['logo', 'favicon', 'login_bg', 'carousel_1', 'carousel_2', 'carousel_3', 'carousel_4'];

        // Validar imagenes: solo archivos de imagen, maximo 5MB
        $validation = [];
        foreach ($imageFields as $field) {
            if ($field === 'favicon') {
                $validation[$field] = 'nullable|file|mimes:jpg,jpeg,png,webp,gif,ico|max:5120';
            } else {
                $validation[$field] = 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120';
            }
        }
        $request->validate($validation);

        $imageService = app(ImageService::class);

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                
                if ($field === 'logo') {
                    $path = $imageService->uploadLogo($file);
                } elseif ($field === 'favicon') {
                    $path = $imageService->uploadFavicon($file);
                } elseif ($field === 'login_bg') {
                    $path = $imageService->uploadLoginBg($file);
                } else {
                    // carousel_1, carousel_2, etc. - treat as hero
                    $path = $imageService->uploadHero($file);
                }

                PageSetting::updateOrCreate(
                    ['key' => $field],
                    ['value' => $path, 'type' => 'image']
                );
            }

            if ($request->has('delete_' . $field)) {
                $setting = PageSetting::where('key', $field)->first();
                if ($setting && $setting->value) {
                    Storage::disk('public')->delete($setting->value);
                    $setting->delete();
                }
            }
        }

        return redirect()->route('admin.page-settings')
            ->with('success', 'Configuración actualizada exitosamente.');
    }
}
