@extends('layouts.admin')

@section('header', 'Detail Pesanan')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Detail Pesanan</h2>
        <p class="text-sm text-gray-500 mt-1 font-mono">{{ $pesanan->nomor_pesanan }}</p>
    </div>
    <a href="{{ route('admin.pesanan.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 flex items-center bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm transition-colors">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left: Items & Status Update --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Items --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Item Pesanan</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pesanan->detailPesanan as $detail)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if(optional($detail->produk)->foto)
                                    <img src="{{ asset('storage/' . $detail->produk->foto) }}" alt="{{ $detail->nama_produk }}" class="w-10 h-10 rounded-md object-cover mr-3 flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-md bg-gray-200 mr-3 flex-shrink-0 flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <span class="text-sm font-medium text-gray-900">{{ $detail->nama_produk }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $detail->jumlah }}</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-600">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 divide-y divide-gray-100">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm text-gray-500">Subtotal</td>
                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm text-gray-500">Ongkos Kirim ({{ strtoupper($pesanan->layanan ?? '-') }})</td>
                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">Rp {{ number_format($pesanan->total_ongkir, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-base font-bold text-gray-900">Total Pembayaran</td>
                        <td class="px-6 py-3 text-right text-base font-bold text-primary-600">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Ubah Status Pesanan</h3>
            <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST" class="space-y-4" x-data="{ status: '{{ $pesanan->status_pesanan }}' }">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-3">
                    <select name="status_pesanan" x-model="status" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @foreach(['pending','dibayar','diproses','dikirim','selesai','dibatalkan'] as $st)
                            <option value="{{ $st }}" {{ $pesanan->status_pesanan == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-md transition-colors shadow-sm">Simpan</button>
                </div>

                {{-- Nomor Resi Field (visible when status is 'dikirim') --}}
                <div x-show="status === 'dikirim'" x-transition class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <label for="nomor_resi" class="block text-sm font-semibold text-blue-800 mb-2">
                        📦 Nomor Resi / Nomor Pengiriman
                        <span class="font-normal text-blue-600">(Wajib diisi saat mengirim)</span>
                    </label>
                    <input type="text" name="nomor_resi" id="nomor_resi"
                           value="{{ $pesanan->nomor_resi }}"
                           placeholder="Contoh: JNE123456789ID"
                           class="block w-full rounded-md border-blue-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm font-mono">
                    <p class="mt-1.5 text-xs text-blue-600">Nomor resi ini akan ditampilkan ke pembeli di halaman detail pesanan mereka.</p>
                </div>

                {{-- Display existing nomor_resi --}}
                @if($pesanan->nomor_resi && $pesanan->status_pesanan !== 'dikirim')
                    {{-- Show only when not in dikirim editing mode --}}
                @endif
            </form>

            @if($pesanan->nomor_resi)
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm text-gray-600">Nomor Resi: <span class="font-mono font-bold text-blue-700">{{ $pesanan->nomor_resi }}</span></p>
            </div>
            @endif
        </div>
    </div>

    {{-- Right: Customer & Payment Info --}}
    <div class="space-y-6">
        {{-- Customer --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Info Pelanggan</h3>
            <div class="space-y-2 text-sm">
                <p class="font-medium text-gray-900">{{ $pesanan->user->name ?? '-' }}</p>
                <p class="text-gray-500">{{ $pesanan->user->email ?? '-' }}</p>
            </div>
        </div>

        {{-- Address --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Alamat Pengiriman</h3>
            @if($pesanan->alamatPengguna)
            <div class="text-sm space-y-1">
                <p class="font-medium text-gray-900">{{ $pesanan->alamatPengguna->nama_penerima }}</p>
                <p class="text-gray-500">{{ $pesanan->alamatPengguna->nomor_telepon }}</p>
                <p class="text-gray-600 mt-2">{{ $pesanan->alamatPengguna->alamat_lengkap }},<br>{{ $pesanan->alamatPengguna->kota }}, {{ $pesanan->alamatPengguna->provinsi }} {{ $pesanan->alamatPengguna->kode_pos }}</p>
            </div>
            @else
                <p class="text-sm text-gray-400 italic">Tidak ada data alamat.</p>
            @endif
        </div>

        {{-- Payment --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Info Pembayaran</h3>
            @if($pesanan->pembayaran)
            <div class="text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="font-medium text-gray-900">{{ strtoupper($pesanan->pembayaran->transaction_status ?? '-') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Metode</span>
                    <span class="font-medium text-gray-900">{{ strtoupper(str_replace('_', ' ', $pesanan->pembayaran->payment_type ?? '-')) }}</span>
                </div>
                @if($pesanan->pembayaran->paid_at)
                <div class="flex justify-between">
                    <span class="text-gray-500">Dibayar</span>
                    <span class="font-medium text-gray-900">{{ $pesanan->pembayaran->paid_at->format('d M Y, H:i') }}</span>
                </div>
                @endif
            </div>
            @else
                <p class="text-sm text-gray-400 italic">Belum ada data pembayaran.</p>
            @endif
        </div>
    </div>
</div>
@endsection
