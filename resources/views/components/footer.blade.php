<footer class="bg-primary-900 text-primary-100 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <!-- Brand -->
            <div class="space-y-4">
                <h3 class="font-display text-2xl font-bold text-white mb-4">WARKO<span class="text-primary-400">.</span></h3>
                <p class="text-primary-200 text-sm leading-relaxed">
                    Menghadirkan tembakau pilihan terbaik dari petani lokal. Kualitas premium dengan cita rasa otentik yang tak tergantikan.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-primary-300 hover:text-white transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465C9.673 2.013 10.03 2 12.484 2h.05zM21 12.336c0-2.625-.01-2.955-.057-3.974-.047-1.023-.207-1.577-.428-2.147a3.73 3.73 0 00-.895-1.354 3.73 3.73 0 00-1.354-.895c-.57-.22-1.124-.381-2.147-.428-.97-.045-1.3-.055-3.816-.057h-.336c-2.488 0-2.822.01-3.816.057-1.023.047-1.577.207-2.147.428a3.73 3.73 0 00-1.354.895 3.73 3.73 0 00-.895 1.354c-.22.57-.381 1.124-.428 2.147-.045.97-.055 1.3-.057 3.816v.336c0 2.488.01 2.822.057 3.816.047 1.023.207 1.577.428 2.147.206.533.483.996.895 1.354.358.412.82.689 1.354.895.57.22 1.124.381 2.147.428 1.019.047 1.349.057 3.974.057h.336c2.457 0 2.812-.01 3.844-.057 1.023-.047 1.577-.207 2.147-.428.533-.206.996-.483 1.354-.895a3.73 3.73 0 00.895-1.354c.22-.57.381-1.124.428-2.147.045-.968.055-1.298.057-3.64l.001-.482zM12 7.23a4.77 4.77 0 100 9.54 4.77 4.77 0 000-9.54zm0 1.258a3.512 3.512 0 110 7.024 3.512 3.512 0 010-7.024zm6.071-3.682a.862.862 0 110 1.724.862.862 0 010-1.724z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-display font-semibold text-white mb-4">Navigasi</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-primary-400 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('produk.index') }}" class="hover:text-primary-400 transition-colors">Katalog Produk</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Kontak</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-display font-semibold text-white mb-4">Bantuan</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Cara Pemesanan</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Pengiriman</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            <!-- Newsletter (Optional) -->
            <div>
                <h4 class="font-display font-semibold text-white mb-4">Berlangganan</h4>
                <p class="text-xs text-primary-300 mb-4">Dapatkan info produk terbaru dan promo menarik.</p>
                <form class="flex">
                    <input type="email" placeholder="Email Anda" class="w-full px-4 py-2 text-sm bg-primary-800 text-white placeholder-primary-400 border border-primary-700 rounded-l focus:outline-none focus:ring-1 focus:ring-primary-400">
                    <button type="button" class="bg-primary-500 hover:bg-primary-600 px-4 py-2 text-white font-medium text-sm rounded-r transition-colors">
                        Kirim
                    </button>
                </form>
            </div>
        </div>

        <div class="border-t border-primary-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-primary-400">
            <p>&copy; {{ date('Y') }} Warko Tembakau. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
