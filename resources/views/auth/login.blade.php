<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Selamat datang kembali</p>
        <h2 class="mt-2 text-3xl font-black tracking-tight text-gray-900">Masuk ke akun Anda</h2>
        <p class="mt-2 text-sm text-gray-500">Lanjutkan mengatur tugas dan prioritas hari ini.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="mt-2 block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 pt-1 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="w-full justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 sm:w-auto">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">
            Daftar sekarang
        </a>
    </p>
</x-guest-layout>
