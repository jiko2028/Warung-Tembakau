@extends('layouts.admin')

@section('header', 'Dashboard Statistik')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name ?? 'Admin' }}! 👋</h2>
    <p class="mt-1 text-sm text-gray-600">Berikut adalah ringkasan performa toko Warko hari ini.</p>
</div>

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card Pendapatan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Card Pesanan Baru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Pesanan Baru (Perlu Dikirim)</p>
            <p class="text-2xl font-bold text-gray-900">{{ $pesananBaru }}</p>
        </div>
    </div>

    <!-- Card Produk -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-lg bg-orange-100 text-orange-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Produk</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalProduk }}</p>
        </div>
    </div>

    <!-- Card Pelanggan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-lg bg-purple-100 text-purple-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalPelanggan }}</p>
        </div>
    </div>
</div>

<!-- Pesanan Terbaru Tabel -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-900">Pesanan Terbaru</h3>
        <a href="{{ route('admin.pesanan.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Order ID</th>
                    <th class="px-6 py-4 font-semibold">Pelanggan</th>
                    <th class="px-6 py-4 font-semibold">Tanggal</th>
                    <th class="px-6 py-4 font-semibold">Total</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pesananTerbaru as $pesanan)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-900">{{ $pesanan->nomor_pesanan }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $pesanan->user->name ?? 'Guest' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $pesanan->created_at->translatedFormat('d M Y, H:i') }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($pesanan->status_pesanan == 'pending')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Pembayaran</span>
                        @elseif($pesanan->status_pesanan == 'dibayar')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Sudah Dibayar</span>
                        @elseif($pesanan->status_pesanan == 'diproses')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Diproses</span>
                        @elseif($pesanan->status_pesanan == 'dikirim')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Dikirim</span>
                        @elseif($pesanan->status_pesanan == 'selesai')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Dibatalkan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
