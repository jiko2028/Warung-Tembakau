<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Warko') }} - @yield('title', 'Tembakau Premium')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
            .font-outfit { font-family: 'Outfit', sans-serif; }
            .font-playfair { font-family: 'Playfair Display', serif; }
        </style>
    </head>
    <body class="font-outfit text-gray-800 bg-paper antialiased selection:bg-primary-200 selection:text-primary-900 overflow-x-hidden"
          x-data="{ loading: false }"
          @loading-start.window="loading = true"
          @loading-end.window="loading = false">
        
        <!-- Global Loading Animation -->
        <div x-show="loading" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] bg-paper/90 backdrop-blur-md flex flex-col items-center justify-center"
             x-cloak>
            <div class="relative w-24 h-24 mb-4">
                <div class="absolute inset-0 border-4 border-primary-200 rounded-full animate-ping opacity-75"></div>
                <div class="absolute inset-0 border-4 border-t-primary-600 border-r-primary-500 border-b-primary-400 border-l-transparent rounded-full animate-spin"></div>
                <div class="absolute inset-4 bg-white rounded-full flex items-center justify-center shadow-inner">
                   <span class="text-primary-800 font-bold font-playfair text-xl">W</span>
                </div>
            </div>
            <span class="text-primary-800 font-medium tracking-widest text-sm animate-pulse">WARKO LOADING...</span>
        </div>

        <div class="min-h-screen flex flex-col transition-opacity duration-500" :class="{ 'opacity-50 blur-sm pointer-events-none': loading }">
            <!-- Navbar -->
            <x-navbar />

            <!-- Main Content -->
            <main class="flex-grow pt-24">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <x-footer />
        </div>
        
        <!-- SweetAlert2 Toast Mixin & Global Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                window.Toast = Toast;
                
                // Intercept clicks to show loading
                document.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function(e) {
                         const href = this.getAttribute('href');
                         if (href && href.startsWith('/') && !href.startsWith('#') && !this.hasAttribute('target')) {
                             // Don't show loading for same page anchors or external links/new tabs
                             document.querySelector('[x-data]').__x.$data.loading = true;
                         }
                    });
                });
                
                // Show session messages if exist
                @if(session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('success') }}",
                        background: '#F3EFEA',
                        color: '#583629'
                    });
                @endif
                
                @if(session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: "{{ session('error') }}",
                        background: '#FEF2F2',
                        color: '#991B1B'
                    });
                @endif
            });
            
            // Loading Helper Global
            window.showLoading = () => window.dispatchEvent(new CustomEvent('loading-start'));
            window.hideLoading = () => window.dispatchEvent(new CustomEvent('loading-end'));
        </script>
        @stack('scripts')
    </body>
</html>
