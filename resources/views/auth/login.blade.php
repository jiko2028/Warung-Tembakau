<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="font-display text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali</h2>
        <p class="text-gray-600 text-sm">Masuk ke akun Anda untuk melanjutkan belanja</p>
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 py-3 transition-colors">
            @error('email')
                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 py-3 transition-colors">
            @error('password')
                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember" 
                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 transition-all">
                <span class="ms-2 text-sm text-gray-600 group-hover:text-primary-600 transition-colors">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="space-y-4">
            <button type="submit" 
                    class="w-full bg-primary-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:bg-primary-700 active:bg-primary-800 transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                Masuk ke Akun
            </button>

            <p class="text-center text-sm text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-bold">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
