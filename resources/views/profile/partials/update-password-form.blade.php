<section>
    <header>
        <h2 class="text-lg font-black text-gray-900 dark:text-white">
            Ubah password
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Gunakan password panjang agar akun tetap aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Password saat ini" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-2 block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:border-slate-500 focus:ring-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Password baru" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-2 block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:border-slate-500 focus:ring-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Konfirmasi password" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:border-slate-500 focus:ring-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="rounded-xl bg-slate-800 px-5 py-2.5 hover:bg-slate-900">Simpan password</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
