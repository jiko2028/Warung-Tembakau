<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // About Us
            ['key' => 'about_title', 'value' => 'Tentang Warko', 'type' => 'text'],
            ['key' => 'about_description', 'value' => 'Warko (Warung Tembakau) berdiri dengan satu misi sederhana: melestarikan kekayaan tembakau Indonesia.', 'type' => 'richtext'],
            ['key' => 'about_hero_image', 'value' => null, 'type' => 'image'],

            // Contact Info
            ['key' => 'contact_address', 'value' => 'Jl. Tembakau No. 12, Kec. Parakan, Temanggung, Jawa Tengah', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+62 812-3456-7890', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'halo@warko.id', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
