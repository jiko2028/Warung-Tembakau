<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $keys = [
            'about_title', 'about_description', 'about_hero_image',
            'contact_address', 'contact_phone', 'contact_email',
            'maps_embed_url',
        ];

        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'about_title'       => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'contact_address'   => 'nullable|string|max:500',
            'contact_phone'     => 'nullable|string|max:50',
            'contact_email'     => 'nullable|email|max:255',
            'maps_embed_url'    => 'nullable|url|max:2000',
        ]);

        $fields = [
            'about_title', 'about_description',
            'contact_address', 'contact_phone', 'contact_email',
            'maps_embed_url',
        ];

        foreach ($fields as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key, '')]);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
