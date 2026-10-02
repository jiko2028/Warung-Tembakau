<x-frontend-layout>
    @section('title', 'Hubungi Kami')

    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="font-display text-4xl font-bold text-gray-900 mb-4">Hubungi Kami</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Ada pertanyaan atau ingin bermitra dengan kami? Jangan ragu untuk menghubungi tim Warko.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Contact Info -->
                <div class="space-y-6">
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 h-full">
                        <h3 class="font-bold text-xl text-gray-900 mb-6">Informasi Kontak</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-primary-50 rounded-full flex items-center justify-center text-primary-600 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Alamat</h4>
                                    <p class="text-gray-600 text-sm mt-1">{!! nl2br(e($settings['contact_address'] ?? 'Alamat belum diatur')) !!}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-primary-50 rounded-full flex items-center justify-center text-primary-600 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Telepon / WhatsApp</h4>
                                    <p class="text-gray-600 text-sm mt-1">{{ $settings['contact_phone'] ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-primary-50 rounded-full flex items-center justify-center text-primary-600 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Email</h4>
                                    <p class="text-gray-600 text-sm mt-1">{{ $settings['contact_email'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                         <!-- Map -->
                        <div class="mt-8 rounded-xl overflow-hidden h-48 bg-gray-200">
                            @if(!empty($settings['maps_embed_url']))
                                <iframe src="{{ $settings['maps_embed_url'] }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 text-sm">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    Peta belum diatur
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-xl text-gray-900 mb-6">Kirim Pesan</h3>
                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="nama" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 shadow-sm py-3" placeholder="Masukkan nama Anda">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 shadow-sm py-3" placeholder="email@contoh.com">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subjek</label>
                                <input type="text" name="subjek" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 shadow-sm py-3" placeholder="Judul pesan anda">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                                <textarea name="pesan" rows="5" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 shadow-sm py-3" placeholder="Tuliskan pesan Anda di sini..."></textarea>
                            </div>
                            <div>
                                <button type="submit" class="w-full md:w-auto bg-primary-600 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:bg-primary-700 transition-all transform hover:-translate-y-0.5">
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>
