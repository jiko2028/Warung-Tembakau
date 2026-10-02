<x-frontend-layout>
    @section('title', $produk->nama)

    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-primary-600">Beranda</a>
                <span class="mx-2">/</span>
                <a href="{{ route('produk.index') }}" class="hover:text-primary-600">Produk</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ $produk->nama }}</span>
            </nav>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                    <!-- Image Gallery -->
                    <div class="p-6 md:p-8 bg-white flex items-center justify-center">
                        <div class="relative w-full aspect-w-4 aspect-h-3 rounded-xl overflow-hidden group">
                             @if($produk->foto)
                                <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama }}" class="object-cover w-full h-full transform transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="flex items-center justify-center h-full bg-gray-100 text-gray-400">
                                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 backdrop-blur text-primary-800 text-sm font-bold px-3 py-1 rounded-full shadow-sm">
                                    {{ $produk->kategori->nama }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-6 md:p-8 md:pl-0 flex flex-col justify-center">
                        <h1 class="font-display text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $produk->nama }}</h1>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            <span class="text-3xl font-bold text-primary-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            @if($produk->stok > 10)
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Stok Tersedia</span>
                            @elseif($produk->stok > 0)
                                <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded">Tersedia (Sisa {{ $produk->stok }})</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Habis</span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2 mb-6 text-sm text-gray-600">
                            <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                Berat: {{ $produk->berat >= 1000 ? ($produk->berat/1000) . ' kg' : $produk->berat . ' gram' }}
                            </span>
                        </div>

                        <div class="prose prose-sm text-gray-600 mb-8 max-w-none">
                            <p>{{ $produk->deskripsi }}</p>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-8 mt-auto">
                            <form action="{{ route('keranjang.store') }}" method="POST" onsubmit="addToCart(event)" class="space-y-4">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                
                                <div class="flex items-center space-x-4">
                                    <div class="w-32">
                                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <input type="number" name="jumlah" id="jumlah" min="1" max="{{ $produk->stok }}" value="1" class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md text-center" {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    
                                     <button type="submit" 
                                        class="flex-1 bg-primary-600 border border-transparent rounded-full py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all shadow-lg hover:shadow-primary-500/30 {{ $produk->stok <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        {{ $produk->stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                                    </button>
                                </div>
                            </form>
                             
                            <div class="mt-6 flex items-center space-x-6 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Original 100%
                                </div>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Pengiriman Aman
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="mt-16">
                    <h2 class="font-display text-2xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $related)
                            <x-product-card :produk="$related" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function addToCart(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            
            const formData = new FormData(form);
            
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
                return response.json().then(err => { throw new Error(err.message || 'Error') });
            })
            .then(data => {
                Toast.fire({
                    icon: 'success',
                    title: 'Produk berhasil ditambahkan!'
                });
                setTimeout(() => window.location.reload(), 1000);
            })
            .catch(error => {
                if(error.message !== 'Unauthenticated') {
                    Toast.fire({
                        icon: 'error',
                        title: error.message
                    });
                     // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
    </script>
    @endpush
</x-frontend-layout>
