<x-frontend-layout>
    @section('title', 'Pembayaran')

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-xl mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="bg-primary-900 p-6 text-white text-center">
                <h2 class="text-2xl font-bold font-display">Pembayaran Pesanan</h2>
                <p class="text-primary-200 mt-2">Selesaikan pembayaran Anda sekarang</p>
            </div>
            
            <div class="p-8">
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <p class="text-sm text-gray-500 mb-1">Nomor Pesanan</p>
                    <p class="text-2xl font-bold font-mono text-gray-800">{{ $pesanan->nomor_pesanan }}</p>
                </div>
                
                <div class="mb-8">
                    <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                    <p class="text-3xl font-bold text-primary-600">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-6">
                    <div class="bg-yellow-50 p-4 rounded-lg text-sm text-yellow-800 border border-yellow-200 flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="font-bold mb-1">Panduan Pembayaran:</p>
                            <ul class="list-disc ml-4 space-y-1">
                                <li>Klik tombol <strong>Bayar Sekarang</strong> di bawah.</li>
                                <li>Pilih metode pembayaran yang tersedia (Virtual Account, E-Wallet, QRIS, dll).</li>
                                <li>Lakukan pembayaran sebelum batas waktu berakhir.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-3">
                       <button id="pay-button" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-200 flex justify-center items-center">
                            <span>Bayar Sekarang</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                       </button>
                       <a href="{{ route('pesanan.show', $pesanan->nomor_pesanan) }}" class="w-full text-center text-gray-500 hover:text-gray-700 font-medium py-2 transition-colors">
                           Kembali ke Detail Pesanan
                       </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        // Debug info
        console.log('Midtrans Snap initialized');
        console.log('Client Key:', '{{ config("midtrans.client_key") }}');
        console.log('Snap Token:', '{{ $snapToken }}');

        document.getElementById('pay-button').onclick = function(){
            console.log('Pay button clicked');
            
            try {
                if (typeof snap === 'undefined') {
                    console.error('Snap library is undefined. Check script loading.');
                    alert('Gagal memuat sistem pembayaran. Coba refresh halaman atau cek koneksi internet.');
                    return;
                }

                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result){
                        console.log('Payment success:', result);
                        window.location.href = "{{ route('pembayaran.sukses', $pesanan->id) }}";
                    },
                    onPending: function(result){
                        console.log('Payment pending:', result);
                        window.location.href = "{{ route('pesanan.show', $pesanan->nomor_pesanan) }}"; // Pending redirect to detail
                    },
                    onError: function(result){
                        console.error('Payment error:', result);
                        window.location.href = "{{ route('pembayaran.gagal', $pesanan->id) }}";
                    },
                    onClose: function(){
                         console.log('Customer closed the popup without finishing the payment');
                    }
                });
            } catch (e) {
                console.error('Exception calling snap.pay:', e);
                alert('Terjadi kesalahan sistem: ' + e.message);
            }
        };
    </script>
    @endpush
</x-frontend-layout>
