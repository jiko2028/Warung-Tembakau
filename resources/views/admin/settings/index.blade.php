@extends('layouts.admin')

@section('header', 'Pengaturan')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Pengaturan Halaman</h2>
    <p class="text-sm text-gray-600 mt-1">Kelola konten halaman Tentang Kami dan Kontak yang tampil di website.</p>
</div>

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-center gap-3 text-sm font-medium">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.pengaturan.update') }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- ========== HALAMAN TENTANG KAMI ========== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Halaman Tentang Kami</h3>
                <p class="text-xs text-gray-500">Tampil di /tentang-kami</p>
            </div>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label for="about_title" class="block text-sm font-medium text-gray-700 mb-1">
                    Judul Halaman
                </label>
                <input type="text" name="about_title" id="about_title"
                       value="{{ old('about_title', $settings['about_title'] ?? '') }}"
                       placeholder="Contoh: Tentang Warko — Warung Tembakau Pilihan"
                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
            </div>
            <div>
                <label for="about_description" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi / Konten Halaman
                </label>
                <textarea name="about_description" id="about_description" rows="8"
                          placeholder="Ceritakan tentang toko, sejarah, misi, visi..."
                          class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('about_description', $settings['about_description'] ?? '') }}</textarea>
                <p class="mt-1.5 text-xs text-gray-500">Bisa menggunakan baris baru untuk memisahkan paragraf.</p>
            </div>
        </div>
    </div>

    {{-- ========== HALAMAN KONTAK ========== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Halaman Kontak</h3>
                <p class="text-xs text-gray-500">Tampil di /kontak</p>
            </div>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Toko</label>
                <textarea name="contact_address" id="contact_address" rows="3"
                          placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan&#10;Kota, Provinsi XXXXX"
                          class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="contact_phone" id="contact_phone"
                           value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                           placeholder="08xxxxxxxxxx"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" name="contact_email" id="contact_email"
                           value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                           placeholder="info@tokoku.com"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>
            </div>
        </div>
    </div>

    {{-- ========== GOOGLE MAPS ========== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Google Maps Embed</h3>
                <p class="text-xs text-gray-500">Peta yang muncul di halaman Kontak</p>
            </div>
        </div>
        <div class="p-6 space-y-5">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
                <p class="font-semibold mb-1">📍 Cara mendapatkan URL Maps:</p>
                <ol class="list-decimal ml-4 space-y-1 text-xs">
                    <li>Buka <a href="https://maps.google.com" target="_blank" class="underline font-medium">Google Maps</a> di browser</li>
                    <li>Cari lokasi toko Anda</li>
                    <li>Klik tombol <strong>Share</strong> → pilih <strong>Embed a map</strong></li>
                    <li>Salin hanya bagian URL dari <code class="bg-amber-100 px-1 rounded">src="..."</code></li>
                    <li>Tempel di kolom di bawah</li>
                </ol>
            </div>

            <div>
                <label for="maps_embed_url" class="block text-sm font-medium text-gray-700 mb-1">
                    URL Embed Google Maps
                    <span class="font-normal text-gray-500">(hanya bagian src="...")</span>
                </label>
                <input type="url" name="maps_embed_url" id="maps_embed_url"
                       value="{{ old('maps_embed_url', $settings['maps_embed_url'] ?? '') }}"
                       placeholder="https://www.google.com/maps/embed?pb=..."
                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm font-mono text-xs">
            </div>

            {{-- Live Preview --}}
            @if(!empty($settings['maps_embed_url']))
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Preview Peta Saat Ini</p>
                <div class="h-56 rounded-xl overflow-hidden border border-gray-200">
                    <iframe src="{{ $settings['maps_embed_url'] }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex justify-end">
        <button type="submit"
                class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
            Simpan Semua Pengaturan
        </button>
    </div>
</form>
@endsection
