@extends('layouts.admin')

@section('header', 'Edit Produk')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Edit Produk</h2>
        <p class="text-sm text-gray-600 mt-1">Mengubah data produk "{{ $produk->nama }}".</p>
    </div>
    <a href="{{ route('admin.produk.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 flex items-center bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm transition-colors">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar
    </a>
</div>

<form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf
    @method('PUT')
    
    <!-- Kolom Kiri: Informasi Dasar -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Informasi Produk</h3>
            
            <div class="space-y-5">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $produk->nama) }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('nama') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                    @error('nama')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" id="deskripsi" rows="6" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('deskripsi') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
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
                        <input type="number" name="harga" id="harga" value="{{ old('harga', $produk->harga) }}" min="0" required class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('harga') border-red-300 @enderror">
                    </div>
                    @error('harga')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok Tersedia <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" id="stok" value="{{ old('stok', $produk->stok) }}" min="0" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('stok') border-red-300 @enderror">
                    @error('stok')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">Berat (Gram) <span class="text-red-500">*</span></label>
                    <input type="number" name="berat" id="berat" value="{{ old('berat', $produk->berat) }}" min="1" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('berat') border-red-300 @enderror">
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
                        <option value="" disabled>-- Pilih Kategori --</option>
                        @foreach($kategoris as $kt)
                            <option value="{{ $kt->id }}" {{ old('kategori_id', $produk->kategori_id) == $kt->id ? 'selected' : '' }}>{{ $kt->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <div class="flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $produk->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 font-medium">Tampilkan di Toko (Aktif)</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Foto Produk</h3>
            
            <div x-data="showImage('{{ $produk->foto ? asset('storage/' . $produk->foto) : '' }}')" class="mt-2">
                <div class="flex flex-col items-center justify-center w-full">
                    <label for="foto" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden relative group transition-colors">
                        
                        <!-- Preview State (Always exists since we pass the old url) -->
                        <img x-show="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover z-0" />
                        
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            <span class="text-white text-sm font-medium tracking-wide bg-gray-900 bg-opacity-80 px-3 py-1.5 rounded-md flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                Ubah Foto
                            </span>
                        </div>
                        
                        <input id="foto" name="foto" type="file" class="hidden" accept="image/*" @change="updatePreview" />
                    </label>
                </div>
                <p class="mt-2 text-xs text-gray-500 text-center">Biarkan kosong jika tidak ingin mengubah foto. (Maks 2MB)</p>
                @error('foto')<p class="mt-2 text-xs text-red-600 text-center font-medium">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl border border-blue-200 p-4">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function showImage(initialUrl) {
        return {
            previewUrl: initialUrl,
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
