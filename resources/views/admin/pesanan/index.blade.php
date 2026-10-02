@extends('layouts.admin')

@section('header', 'Manajemen Pesanan')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Kelola Pesanan</h2>
    <p class="text-sm text-gray-600 mt-1">Pantau dan perbarui status pesanan dari pelanggan.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Filter Bar Skeleton (Opsional) -->
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <div class="text-sm font-medium text-gray-600">Semua Pesanan Transaksi</div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal & Total</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ubah Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($pesanans as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-bold text-gray-900 font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $item->nomor_pesanan }}</span>
                        @if($item->pembayaran && $item->pembayaran->payment_type)
                            <div class="text-xs text-gray-500 mt-1">{{ strtoupper(str_replace('_', ' ', $item->pembayaran->payment_type)) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $item->user->name ?? 'Guest' }}</div>
                        <div class="text-xs text-gray-500">{{ $item->user->email ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-bold">Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $item->created_at->format('d M Y, H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PUT')
                            <select name="status_pesanan" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 {{ 
                                $item->status_pesanan == 'pending' ? 'bg-yellow-50 text-yellow-800' : 
                                ($item->status_pesanan == 'dibayar' ? 'bg-blue-50 text-blue-800 font-semibold' : 
                                ($item->status_pesanan == 'diproses' ? 'bg-purple-50 text-purple-800 font-semibold' : 
                                ($item->status_pesanan == 'dikirim' ? 'bg-indigo-50 text-indigo-800 font-semibold' : 
                                ($item->status_pesanan == 'selesai' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800')))) }}">
                                <option value="pending" {{ $item->status_pesanan == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="dibayar" {{ $item->status_pesanan == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                <option value="diproses" {{ $item->status_pesanan == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikirim" {{ $item->status_pesanan == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $item->status_pesanan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $item->status_pesanan == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded transition border border-gray-200 shadow-sm" title="Simpan Status">Simpan</button>
                        </form>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex flex-col items-end gap-2">
                            <a href="{{ route('admin.pesanan.show', $item->id) }}" class="text-primary-600 hover:text-primary-900 transition-colors font-semibold bg-primary-50 px-3 py-1.5 rounded-lg text-xs">Rincian</a>

                            {{-- Nomor Resi Mini Form --}}
                            @if(in_array($item->status_pesanan, ['dibayar', 'diproses', 'dikirim']))
                            <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST" class="flex items-center gap-1.5 w-full justify-end">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_pesanan" value="{{ $item->status_pesanan === 'dikirim' ? 'dikirim' : $item->status_pesanan }}">
                                <input type="text" name="nomor_resi" value="{{ $item->nomor_resi }}"
                                    placeholder="No. Resi..."
                                    class="text-xs font-mono border border-gray-300 rounded-md px-2 py-1 w-36 focus:ring-blue-500 focus:border-blue-500 {{ $item->nomor_resi ? 'border-blue-400 bg-blue-50' : '' }}">
                                <button type="submit" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-md font-medium transition-colors" title="Simpan Nomor Resi">📦</button>
                            </form>
                            @elseif($item->nomor_resi)
                            <p class="text-xs font-mono text-blue-600 font-semibold">📦 {{ $item->nomor_resi }}</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pesanan</h3>
                        <p class="mt-1 text-sm text-gray-500">Ayo promosikan produk Anda ke pelanggan!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($pesanans->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $pesanans->links() }}
    </div>
    @endif
</div>
@endsection
