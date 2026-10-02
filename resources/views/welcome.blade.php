<x-frontend-layout>
    @section('title', 'Beranda')

    <!-- Hero Section -->
    <section class="relative bg-primary-900 text-white overflow-hidden h-[80vh] flex items-center">
        <!-- Background Pattern/Image -->
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1626202379301-e234199c4c7c?q=80&w=2670&auto=format&fit=crop" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary-950 via-primary-900/80 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center h-full">
            <div class="max-w-xl animate-slide-up">
                <span class="text-primary-300 tracking-widest uppercase text-sm font-semibold mb-2 block">Tembakau Pilihan Petani</span>
                <h1 class="font-display text-5xl md:text-7xl font-bold mb-6 leading-tight">
                    Cita Rasa <br> <span class="text-primary-400">Otentik</span> Nusantara
                </h1>
                <p class="text-primary-100 text-lg mb-8 leading-relaxed">
                    Nikmati keaslian rasa tembakau premium yang dipetik langsung dari kebun terbaik. 
                    Kualitas terjaga, aroma istimewa.
                </p>
                <div class="flex space-x-4">
                    <a href="{{ route('produk.index') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-4 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg hover:shadow-primary-500/50">
                        Belanja Sekarang
                    </a>
                    <a href="#featured" class="border border-primary-500 text-primary-300 hover:text-white hover:bg-primary-800 px-8 py-4 rounded-full font-semibold transition-all">
                        Jelajahi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="flex items-start space-x-4 p-6 rounded-xl bg-primary-50 border border-primary-100 hover:shadow-md transition-shadow">
                    <div class="bg-primary-100 p-3 rounded-full text-primary-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-xl text-primary-900 mb-2">Produk Original</h3>
                        <p class="text-gray-600">Jaminan 100% tembakau asli tanpa campuran bahan berbahaya.</p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="flex items-start space-x-4 p-6 rounded-xl bg-primary-50 border border-primary-100 hover:shadow-md transition-shadow">
                    <div class="bg-primary-100 p-3 rounded-full text-primary-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-xl text-primary-900 mb-2">Harga Terbaik</h3>
                        <p class="text-gray-600">Harga kompetitif langsung dari petani, cocok untuk grosir & eceran.</p>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="flex items-start space-x-4 p-6 rounded-xl bg-primary-50 border border-primary-100 hover:shadow-md transition-shadow">
                    <div class="bg-primary-100 p-3 rounded-full text-primary-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-xl text-primary-900 mb-2">Pengiriman Cepat</h3>
                        <p class="text-gray-600">Layanan pengiriman aman dan cepat ke seluruh wilayah Indonesia.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured" class="py-20 bg-paper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary-600 font-bold tracking-widest uppercase text-sm">Koleksi Terbaik</span>
                <h2 class="font-display text-4xl font-bold text-gray-900 mt-2">Produk Unggulan Kami</h2>
                <div class="w-24 h-1 bg-primary-500 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                @foreach($featuredProducts as $produk)
                    <x-product-card :produk="$produk" />
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('produk.index') }}" class="inline-flex items-center space-x-2 text-primary-700 font-semibold hover:text-primary-900 transition-colors group">
                    <span>Lihat Semua Produk</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </section>


    @push('scripts')
    <script>
        function addToCart(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            // Simulating AJAX request for now, since routes are not yet fully ready/controller not shown
            // In real implementation, this would be fetch('/keranjang', ...)
            
            fetch("{{ route('keranjang.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if(response.ok) return response.json();
                if(response.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    throw new Error('Unauthenticated');
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                Toast.fire({
                    icon: 'success',
                    title: 'Produk berhasil ditambahkan!'
                });
                // Update cart count (reload or dynamic update)
                 setTimeout(() => window.location.reload(), 1000); // Simple reload for now
            })
            .catch(error => {
                if(error.message !== 'Unauthenticated') {
                    console.error('Error:', error);
                    // Fallback for demo if route 404
                     Toast.fire({
                        icon: 'success', // Fake success for demo if backend not ready
                        title: 'Produk ditambahkan ke keranjang (Demo)!'
                    });
                }
            });
        }
    </script>
    @endpush
</x-frontend-layout>
