<x-frontend-layout>
    @section('title', 'Riwayat Pesanan')

    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-display text-3xl font-bold text-gray-900 mb-8">Riwayat Pesanan</h1>

            @if($pesanans->count() > 0)
                <div class="space-y-6">
                    @foreach($pesanans as $pesanan)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                                    <div>
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="text-sm text-gray-500">No. Pesanan</span>
                                            <span class="font-mono font-bold text-gray-900">{{ $pesanan->nomor_pesanan }}</span>
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $pesanan->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{-- Status Pesanan Badge --}}
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-gray-100 text-gray-800',
                                                'dibayar' => 'bg-yellow-100 text-yellow-800',
                                                'diproses' => 'bg-blue-100 text-blue-800',
                                                'dikirim' => 'bg-purple-100 text-purple-800',
                                                'selesai' => 'bg-green-100 text-green-800',
                                                'dibatalkan' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusLabel = ucfirst($pesanan->status_pesanan);
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$pesanan->status_pesanan] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 py-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-xs text-gray-500 block">Total Pembayaran</span>
                                            <span class="font-bold text-primary-600">{{ $pesanan->total_pembayaran_format }}</span>
                                        </div>
                                        <div>
                                            <span class="text-xs text-gray-500 block text-right">Total Berat</span>
                                            <span class="text-sm font-medium">{{ $pesanan->total_berat_kg }} kg</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($pesanan->status_pesanan === 'dibatalkan')
                                    <div class="bg-red-50 p-3 rounded-lg mb-4 text-xs text-red-700">
                                        <strong>Dibatalkan:</strong> {{ Str::limit($pesanan->catatan, 100) }}
                                    </div>
                                @endif

                                <div class="mt-4 flex justify-end">
                                    <a href="{{ route('pesanan.show', $pesanan->nomor_pesanan) }}" class="inline-flex items-center px-4 py-2 border border-primary-600 rounded-full text-sm font-medium text-primary-600 hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    {{ $pesanans->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-xl shadow-sm">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6 text-gray-400">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h2>
                    <p class="text-gray-500 mb-8">Kamu belum pernah melakukan pemesanan apapun.</p>
                    <a href="{{ route('produk.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-frontend-layout>
