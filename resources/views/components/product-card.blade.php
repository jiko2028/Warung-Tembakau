@props(['produk'])

<div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-soft hover:shadow-primary-500/10 transition-all duration-500 border border-gray-50 flex flex-col h-full overflow-hidden">
    <!-- Image -->
    <div class="aspect-w-4 aspect-h-3 bg-cream relative overflow-hidden">
        @if($produk->foto)
            <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama }}" class="object-cover w-full h-full transform group-hover:scale-110 transition-transform duration-700 ease-in-out">
        @else
            <div class="flex items-center justify-center h-full bg-cream text-primary-200">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif
        
        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        <!-- Out of Stock Overlay -->
        @if($produk->stok <= 0)
            <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 flex items-center justify-center">
                <span class="bg-red-600 text-white font-bold text-sm px-3 py-1 rounded shadow-lg transform -rotate-6">HABIS</span>
            </div>
        @endif

        <!-- Badges -->
        <div class="absolute top-3 left-3 flex flex-wrap gap-1 z-10">
             <span class="bg-white/90 backdrop-blur-md text-primary-800 text-[10px] font-bold tracking-wider uppercase px-3 py-1 rounded-full shadow-sm border border-white/50">
                {{ $produk->kategori->nama }}
            </span>
            <span class="bg-black/50 backdrop-blur-md text-white text-[10px] font-bold tracking-wider uppercase px-3 py-1 rounded-full shadow-sm border border-white/20">
                {{ $produk->berat >= 1000 ? ($produk->berat/1000) . ' kg' : $produk->berat . ' gr' }}
            </span>
        </div>
        
        <!-- Quick Action (Visible on Hover Desktop) -->
        <div class="absolute bottom-4 right-4 translate-y-12 group-hover:translate-y-0 transition-transform duration-300 z-10 hidden md:block">
            <form action="{{ route('keranjang.store') }}" method="POST" onsubmit="addToCart(event)">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                <input type="hidden" name="jumlah" value="1">
                <button type="submit" class="bg-white text-primary-600 hover:bg-primary-600 hover:text-white p-3 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 focus:outline-none" title="Tambah Cepat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="p-5 flex flex-col flex-grow">
        <h3 class="font-outfit font-bold text-lg text-gray-900 mb-1 line-clamp-2 group-hover:text-primary-600 transition-colors">
            <a href="{{ route('produk.show', $produk->slug) }}">
                <span aria-hidden="true" class="absolute inset-0"></span>
                {{ $produk->nama }}
            </a>
        </h3>
        
        <!-- Rating Dummy -->
        <div class="flex items-center mb-3">
             <div class="flex text-yellow-400 text-xs">
                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <span class="text-xs text-gray-400 ml-1">(5.0)</span>
        </div>

        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-50">
            <div class="flex flex-col">
                <span class="text-xs text-gray-400 font-medium uppercase tracking-wide">Harga</span>
                <span class="font-playfair font-bold text-xl text-primary-700">
                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                </span>
            </div>
            
            <!-- Mobile Cart Button -->
            <div class="md:hidden relative z-20">
                <form action="{{ route('keranjang.store') }}" method="POST" onsubmit="addToCart(event)">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                    <input type="hidden" name="jumlah" value="1">
                    <button type="submit" class="bg-primary-50 text-primary-600 p-2 rounded-lg hover:bg-primary-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
