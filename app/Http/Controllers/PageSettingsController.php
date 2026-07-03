<?php

namespace App\Http\Controllers;

use App\Models\PageSetting;
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

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('settings', 'public');
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
