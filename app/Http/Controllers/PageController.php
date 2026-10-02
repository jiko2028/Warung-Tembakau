<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Pesan;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $settings = Setting::whereIn('key', ['about_title', 'about_description', 'about_hero_image'])->pluck('value', 'key');
        return view('pages.about', compact('settings'));
    }

    public function contact()
    {
        $settings = Setting::whereIn('key', ['contact_address', 'contact_phone', 'contact_email', 'maps_embed_url'])->pluck('value', 'key');
        return view('pages.contact', compact('settings'));
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        Pesan::create($validated);

        return back()->with('success', 'Pesan Anda berhasil dikirim! Tim kami akan segera menghubungi Anda.');
    }
}
