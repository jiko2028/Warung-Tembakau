@extends('layouts.admin')

@section('header', 'Tambah Produk')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h2>
        <p class="text-sm text-gray-600 mt-1">Lengkapi form di bawah untuk menambahkan produk ke katalog toko.</p>
    </div>
    <a href="{{ route('admin.produk.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 flex items-center bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm transition-colors">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar
    </a>
</div>

<form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf
    
    <!-- Kolom Kiri: Informasi Dasar -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Informasi Produk</h3>
            
            <div class="space-y-5">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('nama') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" placeholder="Contoh: Tembakau Gayo Super">
                    @error('nama')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" id="deskripsi" rows="6" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('deskripsi') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" placeholder="Jelaskan cita rasa, aroma, dan keunggulan...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Harga & Inventori</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="harga" id="harga" value="{{ old('harga') }}" min="0" required class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('harga') border-red-300 @enderror" placeholder="15000">
                    </div>
                    @error('harga')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok Tersedia <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" id="stok" value="{{ old('stok', 0) }}" min="0" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('stok') border-red-300 @enderror" placeholder="100">
                    @error('stok')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">Berat (Gram) <span class="text-red-500">*</span></label>
                    <input type="number" name="berat" id="berat" value="{{ old('berat', 100) }}" min="1" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('berat') border-red-300 @enderror" placeholder="100">
                    @error('berat')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Kategori & Media -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Kategori & Visibilitas</h3>
            
            <div class="space-y-5">
                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori Produk <span class="text-red-500">*</span></label>
                    <select id="kategori_id" name="kategori_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('kategori_id') border-red-300 @enderror">
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($kategoris as $kt)
                            <option value="{{ $kt->id }}" {{ old('kategori_id') == $kt->id ? 'selected' : '' }}>{{ $kt->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <div class="flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 font-medium">Tampilkan di Toko (Aktif)</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Foto Produk <span class="text-red-500">*</span></h3>
            
            <div x-data="showImage()" class="mt-2">
                <div class="flex flex-col items-center justify-center w-full">
                    <label for="foto" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden relative group transition-colors">
                        
                        <!-- Placeholder State -->
                        <div x-show="!previewUrl" class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold text-primary-600">Klik untuk upload</span></p>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP (Maks. 2MB)</p>
                        </div>

                        <!-- Preview State -->
                        <img x-show="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover z-0" style="display: none;" />
                        <div x-show="previewUrl" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10" style="display: none;">
                            <span class="text-white text-sm font-medium tracking-wide bg-gray-900 bg-opacity-70 px-3 py-1.5 rounded">Ganti Foto</span>
                        </div>
                        
                        <input id="foto" name="foto" type="file" class="hidden" accept="image/*" @change="updatePreview" required />
                    </label>
                </div>
                @error('foto')<p class="mt-2 text-xs text-red-600 text-center font-medium">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Simpan Ke Katalog
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function showImage() {
        return {
            previewUrl: '',
            updatePreview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.previewUrl = URL.createObjectURL(file);
                }
            }
        }
    }
</script>
@endpush
@endsection
