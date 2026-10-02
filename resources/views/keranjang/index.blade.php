<x-frontend-layout>
    @section('title', 'Keranjang Belanja')

    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-display text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

            @if($cartItems->count() > 0)
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Cart Items -->
                    <div class="flex-1 bg-white rounded-xl shadow-sm overflow-hidden">
                        <ul class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <li class="p-6 flex items-center sm:items-start" id="item-{{ $item->id }}">
                                    <!-- Image -->
                                    <div class="flex-shrink-0 w-24 h-24 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        @if($item->produk->foto)
                                            <img src="{{ Storage::url($item->produk->foto) }}" alt="{{ $item->produk->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="ml-6 flex-1 flex flex-col sm:flex-row sm:justify-between">
                                        <div class="flex flex-col">
                                            <h3 class="text-lg font-bold text-gray-900">
                                                <a href="{{ route('produk.show', $item->produk->slug) }}" class="hover:text-primary-600">
                                                    {{ $item->produk->nama }}
                                                </a>
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ $item->produk->kategori->nama }} • 
                                                <span class="font-medium">{{ $item->produk->berat >= 1000 ? ($item->produk->berat/1000) . ' kg' : $item->produk->berat . ' gram' }}</span>
                                            </p>
                                            <p class="mt-2 font-bold text-primary-600">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                                            <p class="mt-1 text-sm text-gray-600">
                                                Subtotal: <span class="font-bold text-gray-900">Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</span>
                                            </p>
                                        </div>

                                        <div class="mt-4 sm:mt-0 flex flex-col items-end justify-between">
                                            <!-- Quantity -->
                                            <div class="flex items-center gap-2">
                                                <!-- Decrease -->
                                                <form action="{{ route('keranjang.updateQty') }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <input type="hidden" name="jumlah" value="{{ $item->jumlah - 1 }}">
                                                    <button type="submit" 
                                                            {{ $item->jumlah <= 1 ? 'disabled' : '' }}
                                                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                        -
                                                    </button>
                                                </form>
                                                
                                                <span class="px-3 py-1 text-gray-900 font-medium min-w-[30px] text-center">{{ $item->jumlah }}</span>
                                                
                                                <!-- Increase -->
                                                <form action="{{ route('keranjang.updateQty') }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <input type="hidden" name="jumlah" value="{{ $item->jumlah + 1 }}">
                                                    <button type="submit" 
                                                            {{ $item->jumlah >= $item->produk->stok ? 'disabled' : '' }}
                                                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                        +
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <div class="mt-1">
                                                @if($item->produk->stok > 10)
                                                    <p class="text-xs text-green-600 font-medium">Stok Tersedia</p>
                                                @elseif($item->produk->stok > 0)
                                                    <p class="text-xs text-orange-600 font-medium">Sisa {{ $item->produk->stok }}</p>
                                                @else
                                                    <p class="text-xs text-red-600 font-medium">Stok Habis</p>
                                                @endif
                                            </div>
                                            
                                            <!-- Remove -->
                                            <form action="{{ route('keranjang.destroy', $item->id) }}" method="POST" class="mt-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium transition-colors"
                                                        onclick="return confirm('Yakin ingin menghapus item ini dari keranjang?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Summary -->
                    <div class="w-full lg:w-96 flex-shrink-0">
                        <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                            <h2 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Belanja</h2>
                            
                            <dl class="space-y-4 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <dt>Total Harga ({{ $cartItems->sum('jumlah') }} barang)</dt>
                                    <dd class="font-medium text-gray-900">
                                        Rp {{ number_format($cartItems->sum(fn($item) => $item->produk->harga * $item->jumlah), 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Total Berat</dt>
                                    <dd class="font-medium text-gray-900">
                                        @php $totalBerat = $cartItems->sum(fn($item) => $item->produk->berat * $item->jumlah); @endphp
                                        {{ $totalBerat >= 1000 ? number_format($totalBerat/1000, 1) . ' kg' : $totalBerat . ' gram' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-4">
                                    <dt class="text-base font-bold text-gray-900">Total Belanja</dt>
                                    <dd class="text-base font-bold text-primary-600">
                                        Rp {{ number_format($cartItems->sum(fn($item) => $item->produk->harga * $item->jumlah), 0, ',', '.') }}
                                    </dd>
                                </div>
                            </dl>
                            <p class="text-xs text-gray-500 mt-2">*Belum termasuk ongkos kirim</p>
                            
                            <div class="mt-6">
                                <a href="{{ route('checkout.index') }}" class="w-full block text-center bg-primary-600 text-white rounded-full py-3 px-4 font-bold hover:bg-primary-700 transition-colors shadow-lg hover:shadow-primary-600/30">
                                    Lanjut ke Checkout
                                </a>
                            </div>
                             <div class="mt-4 text-center">
                                <a href="{{ route('produk.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    Lanjut Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-xl shadow-sm">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6 text-gray-400">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Keranjang Belanja Kosong</h2>
                    <p class="text-gray-500 mb-8">Wah, keranjang belanjaanmu masih kosong nih. Yuk isi dengan produk pilihanmu!</p>
                    <a href="{{ route('produk.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-frontend-layout>
