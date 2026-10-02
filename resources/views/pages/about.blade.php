<x-frontend-layout>
    @section('title', 'Tentang Kami')

    <!-- Hero Section -->
    <div class="relative bg-primary-900 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1626202379301-e234199c4c7c?q=80&w=2670&auto=format&fit=crop" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-primary-900 via-primary-900/80 to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl md:text-5xl font-bold mb-4 animate-slide-up">Tentang Warko</h1>
            <p class="text-primary-100 text-lg max-w-2xl mx-auto animate-slide-up animation-delay-200">
                Menghadirkan cita rasa tembakau nusantara terbaik langsung dari petani lokal ke tangan Anda.
            </p>
        </div>
    </div>

    <!-- Story Section -->
    <div class="py-16 bg-paper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1">
                    <div class="relative rounded-2xl overflow-hidden shadow-xl transform rotate-2 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1533038590840-1cde6e668a91?q=80&w=2574&auto=format&fit=crop" alt="Kebun Tembakau" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="order-1 md:order-2 space-y-6">
                    <h2 class="font-display text-3xl font-bold text-gray-900">Warisan Budaya & Rasa</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Warko (Warung Tembakau) berdiri dengan satu misi sederhana: melestarikan kekayaan tembakau Indonesia. Kami percaya bahwa setiap lembar daun tembakau memiliki cerita—tentang tanah tempat ia tumbuh, tentang tangan petani yang merawatnya, dan tentang tradisi yang diwariskan turun-temurun.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        Kami bekerja sama langsung dengan petani tembakau pilihan di berbagai daerah penghasil tembakau terbaik di Indonesia. Dengan memotong rantai distribusi yang panjang, kami memastikan petani mendapatkan harga yang layak dan Anda mendapatkan kualitas produk yang terjamin kesegarannya.
                    </p>
                    <div class="pt-4">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-bold text-primary-700 text-xl font-display">100%</h4>
                                <p class="text-sm text-gray-500">Bahan Alami</p>
                            </div>
                            <div>
                                <h4 class="font-bold text-primary-700 text-xl font-display">50+</h4>
                                <p class="text-sm text-gray-500">Mitra Petani</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display text-3xl font-bold text-gray-900">Nilai Kami</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 bg-primary-50 rounded-2xl text-center hover:shadow-soft transition-shadow">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm text-primary-600">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-3">Kualitas Terjamin</h3>
                    <p class="text-gray-600 text-sm">Setiap produk melalui proses kurasi ketat untuk memastikan aroma dan rasa yang konsisten.</p>
                </div>
                <div class="p-8 bg-primary-50 rounded-2xl text-center hover:shadow-soft transition-shadow">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm text-primary-600">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-3">Dendukung Petani Lokal</h3>
                    <p class="text-gray-600 text-sm">Membeli di Warko berarti turut serta menyejahterakan kehidupan petani tembakau lokal.</p>
                </div>
                <div class="p-8 bg-primary-50 rounded-2xl text-center hover:shadow-soft transition-shadow">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm text-primary-600">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-3">Harga Transparan</h3>
                    <p class="text-gray-600 text-sm">Kami menawarkan harga terbaik yang adil bagi petani dan terjangkau bagi penikmat tembakau.</p>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>
