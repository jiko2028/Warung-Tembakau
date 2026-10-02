<x-frontend-layout>
    @section('title', 'Katalog Produk')

    <div class="bg-primary-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="font-display text-4xl font-bold text-gray-900 mb-4">Katalog Produk</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan berbagai varian tembakau premium pilihan dengan cita rasa otentik yang khas.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar Filters -->
                <div class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                        <h3 class="font-bold text-lg mb-4 text-gray-900">Filter</h3>
                        
                        <!-- Search -->
                        <div class="mb-6">
                            <form action="{{ route('produk.index') }}" method="GET">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-primary-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </button>
                                </div>
                                @if(request('kategori'))
                                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                            </form>
                        </div>

                        <!-- Categories -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3">Kategori</h4>
                            <div class="space-y-2">
                                <a href="{{ route('produk.index', request()->except('kategori')) }}" class="block text-sm {{ !request('kategori') ? 'text-primary-600 font-bold' : 'text-gray-600 hover:text-primary-600' }}">
                                    Semua Kategori
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('produk.index', array_merge(request()->all(), ['kategori' => $category->slug])) }}" class="block text-sm {{ request('kategori') == $category->slug ? 'text-primary-600 font-bold' : 'text-gray-600 hover:text-primary-600' }}">
                                        {{ $category->nama }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sort -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Urutkan</h4>
                            <div class="space-y-2">
                                <a href="{{ route('produk.index', array_merge(request()->all(), ['sort' => 'newest'])) }}" class="block text-sm {{ request('sort', 'newest') == 'newest' ? 'text-primary-600 font-bold' : 'text-gray-600 hover:text-primary-600' }}">
                                    Terbaru
                                </a>
                                <a href="{{ route('produk.index', array_merge(request()->all(), ['sort' => 'price_asc'])) }}" class="block text-sm {{ request('sort') == 'price_asc' ? 'text-primary-600 font-bold' : 'text-gray-600 hover:text-primary-600' }}">
                                    Harga Terendah
                                </a>
                                <a href="{{ route('produk.index', array_merge(request()->all(), ['sort' => 'price_desc'])) }}" class="block text-sm {{ request('sort') == 'price_desc' ? 'text-primary-600 font-bold' : 'text-gray-600 hover:text-primary-600' }}">
                                    Harga Tertinggi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="flex-1">
                    <div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 800)">
                        <!-- Skeleton Loader -->
                        <div x-show="loading" class="grid grid-cols-2 md:grid-cols-3 gap-6" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            @for($i = 0; $i < 6; $i++)
                                <x-product-skeleton />
                            @endfor
                        </div>

                        <!-- Actual Content -->
                        <div x-show="!loading" class="grid grid-cols-2 md:grid-cols-3 gap-6" style="display: none;"
                             x-transition:enter="transition ease-out duration-500 delay-300" 
                             x-transition:enter-start="opacity-0 translate-y-4" 
                             x-transition:enter-end="opacity-100 translate-y-0">
                            @if($products->count() > 0)
                                @foreach($products as $produk)
                                    <x-product-card :produk="$produk" />
                                @endforeach
                            @else
                                <div class="col-span-full text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-50 rounded-full mb-4 text-primary-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 font-display">Produk tidak ditemukan</h3>
                                    <p class="mt-2 text-gray-500 max-w-sm mx-auto">Maaf, kami tidak dapat menemukan produk yang sesuai dengan kriteria pencarian Anda.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('produk.index') }}" class="inline-flex items-center px-6 py-2.5 border border-primary-200 shadow-sm text-sm font-medium rounded-full text-primary-700 bg-white hover:bg-primary-50 focus:outline-none transition-colors">
                                            Reset Filter
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                        
                    @if($products->count() > 0)
                        <div class="mt-10">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function addToCart(event) {
            // Reusing the same function from home page (should generally be in app.js or layout if used multiple times)
             event.preventDefault();
            const form = event.target;
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
                throw new Error('Network response was not ok');
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
                    console.error('Error:', error);
                }
            });
        }
    </script>
    @endpush
</x-frontend-layout>
