<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-2">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

            <!-- Logo & Title -->
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('logo-ken.jpeg') }}" alt="Ken Motor Logo" class="w-24 h-24 mb-3">
                <h1 class="text-2xl font-bold text-gray-800 tracking-wide">Ken Motor Management</h1>
                <p class="text-gray-500 text-sm mt-1">Masuk untuk mengelola showroom Anda</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                    <x-text-input id="email"
                        class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-lg"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                    <x-text-input id="password"
                        class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-lg"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                            href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <x-primary-button
                        class="w-full justify-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition">
                        {{ __('Login') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Register Link -->
            @if (Route::has('register'))
                <p class="mt-6 text-center text-sm text-gray-600">
                    {{ __("Belum punya akun?") }}
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                        {{ __('Daftar Sekarang') }}
                    </a>
                </p>
            @endif
        </div>
    </div>
</x-guest-layout>