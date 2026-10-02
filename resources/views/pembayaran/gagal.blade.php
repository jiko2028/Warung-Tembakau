<x-frontend-layout>
    @section('title', 'Pembayaran Gagal')

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-xl p-8 text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            
            <h2 class="text-3xl font-bold font-display text-gray-900 mb-2">Pembayaran Gagal</h2>
            <p class="text-gray-600 mb-8">Mohon maaf, terjadi kesalahan saat memproses pembayaran Anda untuk pesanan <strong>{{ $pesanan->nomor_pesanan }}</strong>.</p>
            
            <div class="flex justify-center space-x-4">
                <a href="{{ route('pembayaran.index', $pesanan->id) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-bold shadow transition-colors">
                    Coba Lagi
                </a>
                <a href="{{ route('pesanan.show', $pesanan->nomor_pesanan) }}" class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-semibold transition-colors">
                    Detail Pesanan
                </a>
            </div>
        </div>
    </div>
</x-frontend-layout>
