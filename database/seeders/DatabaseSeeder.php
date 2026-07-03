<?php

namespace Database\Seeders;

use App\Models\PageSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Global',
            'email' => 'admin@unas.edu.pe',
            'password' => Hash::make('admin123'),
            'role' => 'admin_global',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin2@unas.edu.pe',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Docente',
            'email' => 'docente@unas.edu.pe',
            'password' => Hash::make('docente123'),
            'role' => 'docente',
            'email_verified_at' => now(),
        ]);

        $defaultSettings = [
            ['key' => 'primary_color', 'value' => '#0B5E2E', 'type' => 'color'],
            ['key' => 'secondary_color', 'value' => '#C9A227', 'type' => 'color'],
            ['key' => 'progress_bar_color', 'value' => '#C9A227', 'type' => 'color'],
            ['key' => 'hero_title', 'value' => 'Sistema de <span>Gestion de Docencia</span> UNAS', 'type' => 'text'],
            ['key' => 'hero_subtitle', 'value' => 'Plataforma oficial de educacion continua de la Universidad Nacional Agraria de la Selva', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '(062) 562341', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'mesadepartes@unas.edu.pe', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => 'Carretera Central Km. 1.21, Tingo Maria', 'type' => 'text'],
            ['key' => 'institution_name', 'value' => 'Universidad Nacional Agraria de la Selva', 'type' => 'text'],
            ['key' => 'institution_acronym', 'value' => 'UNAS', 'type' => 'text'],
            ['key' => 'meta_description', 'value' => 'Plataforma oficial de educación continua de la Universidad Nacional Agraria de la Selva', 'type' => 'text'],
            ['key' => 'footer_text', 'value' => '© 2024 Universidad Nacional Agraria de la Selva. Todos los derechos reservados.', 'type' => 'text'],
            ['key' => 'social_facebook', 'value' => '', 'type' => 'text'],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'text'],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'text'],
        ];

        foreach ($defaultSettings as $setting) {
            PageSetting::create($setting);
        }
    }
}
