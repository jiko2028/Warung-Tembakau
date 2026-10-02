<x-frontend-layout>
    @section('title', 'Checkout')

    <div class="bg-gray-50 py-12" x-data="{
        selectedAddress: {{ optional($addresses->where('is_default', true)->first())->id ?? 'null' }},
        shippingMethod: null,
        baseShippingCost: 0,
        shippingCost: 0,
        totalWeight: 0,
        subtotal: {{ $cartItems->sum(fn($item) => $item->produk->harga * $item->jumlah) }},
        showAddressModal: false,
        isLoadingShipping: false,
        get total() {
            return this.subtotal + this.shippingCost;
        },
        fetchShipping() {
            if(!this.selectedAddress) return;
            
            this.isLoadingShipping = true;
            this.shippingMethod = null;
            this.baseShippingCost = 0;
            this.shippingCost = 0;
            
            fetch('{{ route('checkout.calculateShipping') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ alamat_id: this.selectedAddress })
            })
            .then(response => response.json())
            .then(data => {
                this.isLoadingShipping = false;
                if(data.success) {
                    this.baseShippingCost = data.ongkir;
                    this.totalWeight = data.berat_gram;
                }
            })
            .catch(error => {
                this.isLoadingShipping = false;
                Toast.fire({ icon: 'error', title: 'Gagal menghitung ongkir' });
            });
        },
        updateCost(multiplier) {
            this.shippingCost = Math.round(this.baseShippingCost * multiplier);
        },
        formatNumber(num) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
        },
        formatWeight(gram) {
            if(gram >= 1000) {
                return (gram / 1000).toFixed(1) + ' kg';
            }
            return gram + ' gram';
        },
        processCheckout() {
            if(!this.selectedAddress || !this.shippingMethod) {
                Toast.fire({ icon: 'warning', title: 'Mohon lengkapi alamat dan pengiriman' });
                return;
            }
            
            showLoading();
            
            fetch('{{ route('checkout.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ 
                    alamat_pengguna_id: this.selectedAddress,
                    kurir: 'jne',
                    layanan: this.shippingMethod,
                    ongkir: this.shippingCost,
                    catatan: ''
                })
            })
            .then(response => {
                if (!response.ok) {
                     return response.json().then(err => { throw new Error(err.message || 'Gagal memproses pesanan') });
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                if(data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pesanan Dibuat!',
                        text: 'Silakan lakukan pembayaran.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#c58a52'
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });
                } else {
                     throw new Error(data.message || 'Gagal memproses pesanan');
                }
            })
            .catch(error => {
                hideLoading();
                Toast.fire({
                    icon: 'error',
                    title: error.message
               });
            });
        }
    }" x-init="if(selectedAddress) fetchShipping()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-display text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

            <form @submit.prevent="processCheckout" class="flex flex-col lg:flex-row gap-8">
                <!-- Left Column -->
                <div class="flex-1 space-y-8">
                    <!-- Address Section -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-bold text-gray-900">Alamat Pengiriman</h2>
                            <button type="button" @click="showAddressModal = true" class="text-sm text-primary-600 hover:text-primary-800 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Alamat
                            </button>
                        </div>

                        @if($addresses->count() > 0)
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($addresses as $address)
                                    <label class="relative border rounded-lg p-4 flex cursor-pointer hover:bg-gray-50 transition-colors" 
                                           :class="{'border-primary-500 ring-1 ring-primary-500 bg-primary-50': selectedAddress == {{ $address->id }}, 'border-gray-200': selectedAddress != {{ $address->id }}}">
                                        <input type="radio" name="address" value="{{ $address->id }}" x-model.number="selectedAddress" @change="fetchShipping()" class="sr-only">
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <span class="font-bold text-gray-900">{{ $address->label }}</span>
                                                @if($address->is_default)
                                                    <span class="bg-primary-100 text-primary-800 text-xs px-2 py-0.5 rounded">Utama</span>
                                                @endif
                                            </div>
                                            <p class="text-gray-900 mt-1">{{ $address->nama_penerima }} ({{ $address->nomor_telepon }})</p>
                                            <p class="text-gray-600 text-sm mt-1">{{ $address->alamat_lengkap }}, {{ $address->kota }}, {{ $address->provinsi }} {{ $address->kode_pos }}</p>
                                        </div>
                                        <div class="ml-4 flex items-center" x-show="selectedAddress == {{ $address->id }}">
                                            <svg class="h-5 w-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <p class="text-gray-500 mb-2">Belum ada alamat tersimpan.</p>
                                <button type="button" @click="showAddressModal = true" class="bg-white border border-primary-300 text-primary-700 px-4 py-2 rounded-md font-medium text-sm hover:bg-primary-50">Tambah Alamat Baru</button>
                            </div>
                        @endif
                    </div>

                    <!-- Shipping Method -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Pengiriman</h2>
                        <div x-show="!selectedAddress" class="text-center py-6 text-gray-500">
                            <svg class="h-12 w-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-sm">Pilih alamat terlebih dahulu</p>
                        </div>
                        <div x-show="selectedAddress && isLoadingShipping" class="text-center py-6 text-gray-500">
                            <svg class="animate-spin h-8 w-8 mx-auto mb-2 text-primary-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            <p class="text-sm">Menghitung ongkir...</p>
                        </div>
                        <div x-show="selectedAddress && !isLoadingShipping && baseShippingCost > 0" class="space-y-4">
                            <label class="relative border rounded-lg p-4 flex cursor-pointer hover:bg-gray-50 transition-colors"
                                   :class="{'border-primary-500 ring-1 ring-primary-500 bg-primary-50': shippingMethod === 'jne_reg', 'border-gray-200': shippingMethod !== 'jne_reg'}">
                                <input type="radio" value="jne_reg" x-model="shippingMethod" @change="updateCost(1)" class="sr-only">
                                <div class="flex-1 flex justify-between items-center">
                                    <div>
                                        <span class="block font-medium text-gray-900">JNE Regular</span>
                                        <span class="block text-sm text-gray-500">Estimasi 2-3 hari</span>
                                    </div>
                                    <span class="font-bold text-gray-900" x-text="formatNumber(baseShippingCost)">-</span>
                                </div>
                            </label>
                            
                             <label class="relative border rounded-lg p-4 flex cursor-pointer hover:bg-gray-50 transition-colors"
                                   :class="{'border-primary-500 ring-1 ring-primary-500 bg-primary-50': shippingMethod === 'jne_yes', 'border-gray-200': shippingMethod !== 'jne_yes'}">
                                <input type="radio" value="jne_yes" x-model="shippingMethod" @change="updateCost(1.75)" class="sr-only">
                                <div class="flex-1 flex justify-between items-center">
                                    <div>
                                        <span class="block font-medium text-gray-900">JNE YES</span>
                                        <span class="block text-sm text-gray-500">Estimasi 1 hari</span>
                                    </div>
                                    <span class="font-bold text-gray-900" x-text="formatNumber(Math.round(baseShippingCost * 1.75))">-</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Summary) -->
                <div class="w-full lg:w-96 flex-shrink-0">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Pesanan</h2>
                        
                        <!-- Items List (Collapsed) -->
                        <div class="mb-6 max-h-48 overflow-y-auto pr-2 scrollbar-thin">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between mb-3 text-sm">
                                    <div class="flex">
                                        <span class="text-gray-500 mr-2">{{ $item->jumlah }}x</span>
                                        <span class="text-gray-900 truncate w-32" title="{{ $item->produk->nama }}">{{ $item->produk->nama }}</span>
                                    </div>
                                    <span class="text-gray-600">Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <dl class="space-y-4 text-sm text-gray-600 border-t border-gray-100 pt-4">
                            <div class="flex justify-between">
                                <dt>Subtotal</dt>
                                <dd class="font-medium text-gray-900">
                                    Rp {{ number_format($cartItems->sum(fn($item) => $item->produk->harga * $item->jumlah), 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="flex justify-between" x-show="totalWeight > 0">
                                <dt>Total Berat</dt>
                                <dd class="font-medium text-gray-900" x-text="formatWeight(totalWeight)">-</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt>Ongkos Kirim</dt>
                                <dd class="font-medium text-gray-900" x-text="formatNumber(shippingCost)">-</dd>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-4">
                                <dt class="text-base font-bold text-gray-900">Total Pembayaran</dt>
                                <dd class="text-xl font-bold text-primary-600" x-text="formatNumber(total)">
                                    -
                                </dd>
                            </div>
                        </dl>
                        
                        <div class="mt-8">
                            <button type="submit" class="w-full bg-primary-600 text-white rounded-full py-4 px-4 font-bold hover:bg-primary-700 transition-all shadow-lg hover:shadow-primary-600/30 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="!selectedAddress || !shippingMethod">
                                Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Modal Tambah Alamat (inside Alpine scope) --}}
        <div x-show="showAddressModal" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="showAddressModal = false"></div>

            {{-- Modal Panel --}}
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     @click.stop>

                    {{-- Header --}}
                    <div class="flex items-center justify-between p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Tambah Alamat Baru</h3>
                        <button type="button" @click="showAddressModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('alamat.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Label <span class="text-red-500">*</span></label>
                                <input type="text" name="label" value="{{ old('label') }}" placeholder="Rumah / Kantor" required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_penerima" value="{{ old('nama_penerima') }}" required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="08xxxxxxxxxx" required
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                                <input type="text" name="provinsi" value="{{ old('provinsi') }}" required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota / Kabupaten <span class="text-red-500">*</span></label>
                                <input type="text" name="kota" value="{{ old('kota') }}" required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos <span class="text-red-500">*</span></label>
                                <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat_lengkap" rows="3" required
                                      placeholder="Nama Jalan, No. Rumah, RT/RW, Dusun/Kelurahan..."
                                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">{{ old('alamat_lengkap') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="showAddressModal = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg shadow-sm transition-colors">
                                Simpan Alamat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>{{-- End Alpine x-data wrapper --}}

</x-frontend-layout>
