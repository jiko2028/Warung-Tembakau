<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Warko') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700|playfair-display:700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Background Image with Overlay -->
        <div class="fixed inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1626202379301-e234199c4c7c?q=80&w=2670&auto=format&fit=crop" 
                 class="w-full h-full object-cover" alt="Background">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/90 via-primary-800/85 to-primary-900/90 backdrop-blur-sm"></div>
        </div>

        <!-- Navbar (Minimalis) -->
        <nav class="relative z-10 bg-white/10 backdrop-blur-md border-b border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                            <span class="text-primary-700 font-bold text-xl font-display">W</span>
                        </div>
                        <span class="font-display text-2xl font-bold text-white">Warko</span>
                    </a>
                    <a href="{{ route('home') }}" class="text-white/90 hover:text-white text-sm font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="relative z-10 min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md animate-slide-up">
                <!-- Glassmorphic Card -->
                <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 sm:p-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
