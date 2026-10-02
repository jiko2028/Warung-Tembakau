<x-frontend-layout>
    @section('title', 'Dashboard Saya')

    <div class="bg-gray-50 py-12" x-data="{ 
        showAddressModal: false, 
        editingAddress: null,
        addressForm: {
            label: '',
            nama_penerima: '',
            nomor_telepon: '',
            provinsi: '',
            kota: '',
            kecamatan: '',
            kode_pos: '',
            alamat_lengkap: ''
        },
        openAddModal() {
            this.editingAddress = null;
            this.addressForm = {
                label: '',
                nama_penerima: '',
                nomor_telepon: '',
                provinsi: '',
                kota: '',
                kecamatan: '',
                kode_pos: '',
                alamat_lengkap: ''
            };
            this.showAddressModal = true;
        },
        openEditModal(address) {
            this.editingAddress = address.id;
            this.addressForm = {
                label: address.label,
                nama_penerima: address.nama_penerima,
                nomor_telepon: address.nomor_telepon,
                provinsi: address.provinsi,
                kota: address.kota,
                kecamatan: address.kecamatan,
                kode_pos: address.kode_pos,
                alamat_lengkap: address.alamat_lengkap
            };
            this.showAddressModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header User -->
            <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8 mb-8 flex flex-col md:flex-row items-center gap-6 border border-gray-100">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center text-3xl font-bold text-primary-700">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-center md:text-left flex-1">
                    <h1 class="font-display text-2xl font-bold text-gray-900">{{ Auth::user()->name }}</h1>
                    <p class="text-gray-500">{{ Auth::user()->email }}</p>
                    <p class="text-sm text-primary-600 mt-1 font-medium">Member sejak {{ Auth::user()->created_at->format('d F Y') }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition-colors text-sm font-medium">
                        Edit Profil
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-10">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-primary-200 transition-colors">
                    <div class="text-gray-500 text-sm font-medium mb-1">Menunggu Bayar</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-primary-200 transition-colors">
                    <div class="text-gray-500 text-sm font-medium mb-1">Diproses</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['processing'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-primary-200 transition-colors">
                    <div class="text-gray-500 text-sm font-medium mb-1">Dikirim</div>
                    <div class="text-3xl font-bold text-primary-600">{{ $stats['shipping'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-primary-200 transition-colors">
                    <div class="text-gray-500 text-sm font-medium mb-1">Selesai</div>
                    <div class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Order History -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-xl font-bold text-gray-900">Riwayat Pesanan</h2>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        @if($orders->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($orders as $order)
                                    <div class="p-6 hover:bg-gray-50 transition-colors">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                                            <div>
                                                <span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-1 rounded mb-2 inline-block">
                                                    {{ $order->nomor_pesanan }}
                                                </span>
                                                <div class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'dibayar' => 'bg-green-100 text-green-800',
                                                        'diproses' => 'bg-blue-100 text-blue-800',
                                                        'dikirim' => 'bg-purple-100 text-purple-800',
                                                        'selesai' => 'bg-green-100 text-green-800',
                                                        'dibatalkan' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $statusLabel = ucfirst($order->status_pesanan);
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$order->status_pesanan] ?? 'bg-gray-100' }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-4 mb-4">
                                            @if($order->detailPesanan->first())
                                                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                                    @if($order->detailPesanan->first()->produk && $order->detailPesanan->first()->produk->foto)
                                                        <img src="{{ Storage::url($order->detailPesanan->first()->produk->foto) }}" class="w-full h-full object-cover">
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="font-bold text-gray-900 text-sm">{{ $order->detailPesanan->first()->produk->nama ?? 'Produk dihapus' }}</h4>
                                                @if($order->detailPesanan->count() > 1)
                                                    <p class="text-xs text-gray-500">+ {{ $order->detailPesanan->count() - 1 }} produk lainnya</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xs text-gray-500">Total Belanja</div>
                                                <div class="font-bold text-primary-700">Rp {{ number_format($order->total_pembayaran, 0, ',', '.') }}</div>
                                            </div>
                                        </div>

                                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                            <a href="{{ route('pesanan.show', $order->nomor_pesanan) }}" class="px-4 py-2 text-primary-600 hover:text-primary-800 text-sm font-medium">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="p-4 border-t border-gray-100">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Belum ada pesanan</h3>
                                <p class="text-gray-500 mb-6">Yuk mulai belanja produk tembakau favoritmu!</p>
                                <a href="{{ route('produk.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Belanja Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Addresses -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-xl font-bold text-gray-900">Alamat Saya</h2>
                        <button @click="openAddModal()" class="text-sm text-primary-600 font-medium hover:text-primary-800">+ Tambah</button>
                    </div>

                    <div class="space-y-4">
                        @forelse($addresses as $address)
                            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:border-primary-200 transition-colors relative group">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-bold text-gray-900">{{ $address->label }}</span>
                                    @if($address->is_default)
                                        <span class="bg-primary-50 text-primary-700 text-[10px] font-bold px-2 py-0.5 rounded border border-primary-100">UTAMA</span>
                                    @endif
                                </div>
                                <p class="text-gray-900 font-medium text-sm">{{ $address->nama_penerima }}</p>
                                <p class="text-gray-500 text-sm mb-1">{{ $address->nomor_telepon }}</p>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ $address->alamat_lengkap }}, {{ $address->kota }}, {{ $address->provinsi }} {{ $address->kode_pos }}</p>
                                
                                <div class="mt-4 flex gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="openEditModal({{ $address }})" class="text-xs font-medium text-primary-600 hover:text-primary-800">Ubah</button>
                                    @if(!$address->is_default)
                                        <form action="{{ route('alamat.destroy', $address->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin hapus alamat ini?')" class="text-xs font-medium text-red-500 hover:text-red-700">Hapus</button>
                                        </form>
                                        <form action="{{ route('alamat.setDefault', $address->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-medium text-gray-600 hover:text-gray-800">Jadikan Utama</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-dashed border-gray-300 text-center">
                                <p class="text-gray-500 text-sm mb-3">Belum ada alamat tersimpan.</p>
                                <button @click="openAddModal()" class="text-primary-600 text-sm font-medium hover:underline">Tambah Alamat Baru</button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Modal -->
        <div x-show="showAddressModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div @click="showAddressModal = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
                
                <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-8">
                    <h3 class="font-display text-2xl font-bold text-gray-900 mb-6" x-text="editingAddress ? 'Edit Alamat' : 'Tambah Alamat Baru'"></h3>
                    
                    <form :action="editingAddress ? '{{ url('/alamat') }}/' + editingAddress : '{{ route('alamat.store') }}'" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" x-bind:value="editingAddress ? 'PATCH' : 'POST'">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Label Alamat</label>
                                <input type="text" name="label" x-model="addressForm.label" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500" placeholder="Rumah, Kantor, dll">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                                <input type="text" name="nama_penerima" x-model="addressForm.nama_penerima" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" x-model="addressForm.nomor_telepon" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                <input type="text" name="provinsi" x-model="addressForm.provinsi" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                                <input type="text" name="kota" x-model="addressForm.kota" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                                <input type="text" name="kecamatan" x-model="addressForm.kecamatan" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                                <input type="text" name="kode_pos" x-model="addressForm.kode_pos" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" x-model="addressForm.alamat_lengkap" rows="3" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500" placeholder="Nama jalan, nomor rumah, RT/RW, dll"></textarea>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="flex-1 bg-primary-600 text-white px-6 py-3 rounded-full font-bold hover:bg-primary-700 transition-colors shadow-lg">
                                <span x-text="editingAddress ? 'Simpan Perubahan' : 'Tambah Alamat'"></span>
                            </button>
                            <button type="button" @click="showAddressModal = false" class="px-6 py-3 border border-gray-300 rounded-full font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>
