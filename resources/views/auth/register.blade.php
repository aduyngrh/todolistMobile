<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Mulai sekarang</p>
        <h2 class="mt-2 text-3xl font-black tracking-tight text-gray-900">Buat akun baru</h2>
        <p class="mt-2 text-sm text-gray-500">Simpan tugas, atur prioritas, dan jaga fokus Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        <div>
            <x-input-label for="name" value="Nama lengkap" />
            <x-text-input id="name" class="mt-2 block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-2 block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="mt-2 block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi password" />
            <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex flex-col gap-4 pt-1 sm:flex-row sm:items-center sm:justify-between">
            <a class="text-sm font-semibold text-gray-500 underline hover:text-indigo-600" href="{{ route('login') }}">Sudah punya akun? Masuk</a>
            <x-primary-button class="w-full justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 sm:w-auto">Daftar</x-primary-button>
        </div>
    </form>
</x-guest-layout>
