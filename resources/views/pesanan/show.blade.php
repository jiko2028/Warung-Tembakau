<x-frontend-layout>
    @section('title', 'Detail Pesanan #' . $pesanan->nomor_pesanan)

    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-sm text-gray-500">
                <a href="{{ route('pesanan.index') }}" class="hover:text-primary-600">Riwayat Pesanan</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">Detail Pesanan</span>
            </nav>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="flex-1 space-y-6">
                    
                    <!-- Status Alert if Cancelled -->
                    @if($pesanan->status_pesanan === 'dibatalkan')
                        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-lg shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 w-full">
                                    <h3 class="text-lg font-medium text-red-800">Pesanan Dibatalkan</h3>
                                    <div class="mt-2 text-sm text-red-700 whitespace-pre-wrap">
                                        <strong>Alasan:</strong> {{ $pesanan->alasan_batal ?? $pesanan->catatan }}
                                    </div>
                                    
                                    @if($pesanan->info_refund)
                                        <div class="mt-4 p-4 bg-white rounded border border-red-100">
                                            <p class="font-bold text-red-800 text-xs uppercase tracking-wide mb-2">Info Pengembalian Dana (Terkirim)</p>
                                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $pesanan->info_refund }}</p>
                                        </div>
                                    @else
                                        <div class="mt-4 p-4 bg-white rounded border border-red-100">
                                            <p class="font-bold text-red-800 text-xs uppercase tracking-wide mb-2">Form Pengembalian Dana</p>
                                            <p class="text-sm text-gray-600 mb-3">Mohon isi data rekening/e-wallet untuk proses refund.</p>
                                            
                                            <form action="{{ route('pesanan.refund', $pesanan->nomor_pesanan) }}" method="POST">
                                                @csrf
                                                <textarea name="info_refund" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Contoh: BCA 1234567890 a.n. Budi Santoso" required></textarea>
                                                <button type="submit" class="mt-2 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Kirim Info Refund
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Order Items -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h2 class="font-bold text-gray-900">Produk yang Dibeli</h2>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @foreach($pesanan->detailPesanan as $detail)
                                <li class="p-6 flex items-center">
                                    <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        @if($detail->produk->foto)
                                            <img src="{{ Storage::url($detail->produk->foto) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-sm font-bold text-gray-900">{{ $detail->nama_produk }}</h4>
                                        <p class="text-xs text-gray-500">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Shipping Info -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h2 class="font-bold text-gray-900">Informasi Pengiriman</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Penerima</h3>
                                <p class="text-gray-900 font-medium">{{ $pesanan->alamatPengguna->nama_penerima }}</p>
                                <p class="text-gray-600 text-sm">{{ $pesanan->alamatPengguna->telepon }}</p>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Alamat</h3>
                                <p class="text-gray-600 text-sm">
                                    {{ $pesanan->alamatPengguna->alamat_lengkap }}<br>
                                    {{ $pesanan->alamatPengguna->kota }}, {{ $pesanan->alamatPengguna->provinsi }}<br>
                                    {{ $pesanan->alamatPengguna->kode_pos }}
                                </p>
                            </div>
                            @if($pesanan->nomor_resi)
                                <div class="col-span-1 md:col-span-2 bg-blue-50 p-4 rounded-lg border border-blue-100">
                                    <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-1">Nomor Resi</h3>
                                    <p class="text-lg font-mono font-bold text-blue-600">{{ $pesanan->nomor_resi }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Summary -->
                <div class="w-full lg:w-96 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden p-6">
                        <h2 class="font-bold text-gray-900 mb-4">Rincian Pembayaran</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>{{ $pesanan->subtotal_format }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Ongkos Kirim ({{ $pesanan->total_berat_kg }} kg)</span>
                                <span>{{ $pesanan->total_ongkir_format }}</span>
                            </div>
                            <div class="border-t border-gray-100 pt-3 flex justify-between font-bold text-lg text-gray-900">
                                <span>Total</span>
                                <span class="text-primary-600">{{ $pesanan->total_pembayaran_format }}</span>
                            </div>
                        </div>

                        <!-- Status Pesanan -->
                        <div class="mb-6 border-b border-gray-100 pb-6">
                            <div class="text-xs text-gray-500 mb-1">Status Pesanan</div>
                            @php
                                $statusLabel = match($pesanan->status_pesanan) {
                                    'pending' => 'Menunggu Konfirmasi',
                                    'dibayar' => 'Sudah Dibayar',
                                    'diproses' => 'Sedang Diproses',
                                    'dikirim' => 'Sedang Dikirim',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan',
                                    default => ucfirst($pesanan->status_pesanan)
                                };
                                $statusColor = match($pesanan->status_pesanan) {
                                    'pending' => 'text-gray-600 bg-gray-100 border-gray-200',
                                    'dibayar' => 'text-blue-600 bg-blue-50 border-blue-200',
                                    'diproses' => 'text-orange-600 bg-orange-50 border-orange-200',
                                    'dikirim' => 'text-purple-600 bg-purple-50 border-purple-200',
                                    'selesai' => 'text-green-600 bg-green-50 border-green-200',
                                    'dibatalkan' => 'text-red-600 bg-red-50 border-red-200',
                                    default => 'text-gray-600 bg-gray-50 border-gray-200'
                                };
                            @endphp
                            <div class="border rounded-lg p-3 text-center font-bold {{ $statusColor }}">
                                {{ $statusLabel }}
                            </div>
                        </div>

                        <!-- Status Payment -->
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Status Pembayaran</div>
                            @php
                                $payStatus = $pesanan->pembayaran?->transaction_status ?? 'pending';
                                $payLabel = match($payStatus) {
                                    'capture', 'settlement' => 'Lunas',
                                    'pending' => 'Menunggu Pembayaran',
                                    'deny', 'expire', 'cancel' => 'Gagal/Kadaluarsa',
                                    default => 'Pending'
                                };
                                $payColor = match($payStatus) {
                                    'capture', 'settlement' => 'text-green-600 bg-green-50 border-green-200',
                                    'pending' => 'text-orange-600 bg-orange-50 border-orange-200',
                                    default => 'text-red-600 bg-red-50 border-red-200'
                                };
                            @endphp
                            <div class="border rounded-lg p-3 text-center font-bold {{ $payColor }}">
                                {{ $payLabel }}
                            </div>

                            @if($pesanan->status_pesanan == 'pending' && $payStatus == 'pending')
                                <a href="{{ route('pembayaran.index', $pesanan->id) }}" class="mt-4 w-full block text-center bg-primary-600 text-white rounded-lg py-3 font-bold hover:bg-primary-700 transition-colors shadow-lg shadow-primary-500/30">
                                    Bayar Sekarang
                                </a>
                            @endif

                            @if($pesanan->status_pesanan == 'dikirim')
                                <form action="{{ route('pesanan.terima', $pesanan->nomor_pesanan) }}" method="POST" class="mt-6 border-t pt-4">
                                    @csrf
                                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-primary-600 text-white rounded-lg py-3 font-bold hover:bg-primary-700 transition-all shadow-lg hover:shadow-primary-500/30 transform hover:-translate-y-0.5" onclick="return confirm('Apakah Anda yakin pesanan sudah diterima dengan baik?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Konfirmasi Pesanan Diterima
                                    </button>
                                    <p class="text-xs text-center text-gray-500 mt-2">Klik tombol ini jika barang sudah sampai.</p>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>
