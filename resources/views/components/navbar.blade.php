<nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-white/80 backdrop-blur-md shadow-soft': scrolled, 'bg-transparent py-4': !scrolled, 'py-2': scrolled }"
     class="fixed w-full z-50 transition-all duration-500 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center group cursor-pointer">
                <a href="{{ route('home') }}" class="font-playfair font-bold text-3xl text-primary-800 tracking-wider flex items-center gap-2">
                    <span class="w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center text-xl font-bold group-hover:bg-primary-500 transition-colors">W</span>
                    <span class="group-hover:text-primary-600 transition-colors">WARKO</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-10 items-center">
                @foreach([
                    ['label' => 'Beranda', 'route' => 'home'],
                    ['label' => 'Katalog', 'route' => 'produk.index'],
                ] as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="relative font-outfit font-medium text-sm tracking-wide transition-colors duration-300
                              {{ request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*') ? 'text-primary-700' : 'text-gray-600 hover:text-primary-600' }}">
                        {{ $item['label'] }}
                        @if(request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*'))
                            <span class="absolute -bottom-2 left-1/2 w-1.5 h-1.5 bg-primary-600 rounded-full transform -translate-x-1/2"></span>
                        @endif
                    </a>
                @endforeach
                <a href="{{ route('about') }}" class="font-outfit font-medium text-sm tracking-wide {{ request()->routeIs('about') ? 'text-primary-700' : 'text-gray-600 hover:text-primary-600' }} transition-colors">Tentang Kami</a>
                <a href="{{ route('contact') }}" class="font-outfit font-medium text-sm tracking-wide {{ request()->routeIs('contact') ? 'text-primary-700' : 'text-gray-600 hover:text-primary-600' }} transition-colors">Kontak</a>
            </div>

            <!-- Icons -->
            <div class="hidden md:flex items-center space-x-6" x-data="{ searchOpen: false }">
                <!-- Search Input (Expandable) -->
                <div class="flex items-center">
                     <div x-show="searchOpen" 
                          x-transition:enter="transition ease-out duration-300"
                          x-transition:enter-start="opacity-0 w-0"
                          x-transition:enter-end="opacity-100 w-64"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100 w-64"
                          x-transition:leave-end="opacity-0 w-0"
                          class="mr-2 relative overflow-hidden">
                        <form action="{{ route('produk.index') }}" method="GET">
                            <input type="text" name="q" placeholder="Cari..." class="w-64 pl-4 pr-4 py-1.5 text-sm border-gray-200 rounded-full focus:ring-primary-500 focus:border-primary-500 bg-white/50 backdrop-blur-sm"
                                   x-ref="searchInput" 
                                   @click.away="if($el.value === '') searchOpen = false"
                                   @keydown.escape="searchOpen = false">
                        </form>
                    </div>

                    <button @click="searchOpen = !searchOpen; $nextTick(() => { if(searchOpen) $refs.searchInput.focus() })" class="text-gray-500 hover:text-primary-600 transition-colors bg-white/50 p-2 rounded-full hover:bg-white shadow-sm hover:shadow">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>

                <!-- Cart -->
                <a href="{{ route('keranjang.index') }}" class="relative text-gray-500 hover:text-primary-600 transition-colors bg-white/50 p-2 rounded-full hover:bg-white shadow-sm hover:shadow group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    @auth
                        @php $cartCount = \App\Models\Keranjang::where('user_id', auth()->id())->count(); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-primary-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white transform group-hover:scale-110 transition-transform">
                                {{ $cartCount }}
                            </span>
                        @endif
                    @endauth
                </a>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition-colors focus:outline-none bg-white py-1.5 px-3 rounded-full border border-gray-100 shadow-sm hover:shadow hover:border-primary-200">
                        @auth
                             <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 font-bold text-xs">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-medium hidden lg:block">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        @else
                             <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-400">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span class="text-sm font-medium hidden lg:block">Akun</span>
                        @endauth
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                         class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl py-2 border border-gray-100 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 pointer-events-auto" style="display: none;">
                        
                        @auth
                            <div class="px-5 py-3 border-b border-gray-50 flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold text-lg">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                     <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                     <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">Dashboard User</a>
                                @if(Auth::user()->role === 'admin')
                                    <a href="/admin/dashboard" class="block px-4 py-2 text-sm text-primary-600 font-semibold hover:bg-primary-50">Admin Panel</a>
                                @endif
                            </div>
                            <div class="border-t border-gray-50 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">Keluar</button>
                                </form>
                            </div>
                        @else
                            <div class="p-2 space-y-2">
                                <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">Masuk</a>
                                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 text-sm font-medium text-primary-700 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors border border-primary-100">Daftar</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-800 p-2 rounded-md hover:bg-primary-50 focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 shadow-xl" style="display: none;">
        <div class="px-4 pt-4 pb-6 space-y-2">
            @foreach([
                ['label' => 'Beranda', 'route' => 'home'],
                ['label' => 'Katalog Produk', 'route' => 'produk.index'],
                ['label' => 'Keranjang Belanja', 'route' => 'keranjang.index'],
            ] as $item)
                <a href="{{ route($item['route']) }}" class="block px-4 py-3 rounded-xl text-base font-medium text-gray-800 hover:text-primary-700 hover:bg-primary-50 transition-all border border-transparent hover:border-primary-100
                {{ request()->routeIs($item['route']) ? 'bg-primary-50 text-primary-700 border-primary-100' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
            
            <div class="h-px bg-gray-100 my-4"></div>
            
            @auth
                <div class="px-4 py-2 flex items-center gap-3 bg-primary-50/50 rounded-xl mb-3">
                    <div class="w-8 h-8 bg-primary-200 rounded-full flex items-center justify-center text-primary-800 font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-900">Halo, {{ Auth::user()->name }}</div>
                        <a href="{{ route('dashboard') }}" class="text-xs text-primary-600 font-medium hover:underline">Ke Dashboard</a>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-3 rounded-xl text-base font-medium text-red-600 hover:bg-red-50 transition-all">Keluar</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <a href="{{ route('login') }}" class="block text-center px-4 py-2.5 rounded-xl text-base font-medium text-white bg-primary-600 hover:bg-primary-700 shadow-md">Masuk</a>
                    <a href="{{ route('register') }}" class="block text-center px-4 py-2.5 rounded-xl text-base font-medium text-primary-700 bg-white border border-primary-200 hover:bg-primary-50">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
