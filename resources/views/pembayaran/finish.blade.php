<x-frontend-layout>
    @section('title', 'Status Pembayaran')

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-xl p-8 text-center">
            
            @if($transactionStatus == 'settlement' || $transactionStatus == 'capture')
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-3xl font-bold font-display text-gray-900 mb-2">Pembayaran Berhasil!</h2>
                <p class="text-gray-600 mb-8">Transaksi Anda telah berhasil diproses.</p>
            @elseif($transactionStatus == 'pending')
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-3xl font-bold font-display text-gray-900 mb-2">Menunggu Pembayaran</h2>
                <p class="text-gray-600 mb-8">Silakan selesaikan pembayaran Anda.</p>
            @else
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h2 class="text-3xl font-bold font-display text-gray-900 mb-2">Pembayaran Gagal/Batal</h2>
                <p class="text-gray-600 mb-8">Status transaksi: {{ $transactionStatus }}</p>
            @endif

            <div class="flex justify-center space-x-4">
                <a href="{{ route('pesanan.index') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-bold shadow transition-colors">
                    Riwayat Pesanan
                </a>
                <a href="{{ route('produk.index') }}" class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-semibold transition-colors">
                    Belanja Lagi
                </a>
            </div>
        </div>
    </div>
</x-frontend-layout>
