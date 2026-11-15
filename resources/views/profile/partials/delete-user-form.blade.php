<section class="space-y-4">
    <header class="flex items-center gap-3 pb-3 border-b border-rose-200/60 dark:border-rose-900/40">
        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 text-lg shadow-sm">
            ⚠️
        </div>
        <div>
            <h2 class="text-lg font-black text-rose-900 dark:text-rose-200">
                Zona Bahaya: Hapus Akun
            </h2>
            <p class="text-xs text-rose-700/80 dark:text-rose-300/80">
                Tindakan ini bersifat permanen. Seluruh data tugas, subtasks, dan catatan akan dihapus secara tetap.
            </p>
        </div>
    </header>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-lg">
            Setelah akun Anda dihapus, semua data tidak dapat dipulihkan kembali. Pastikan Anda telah mencadangkan data penting sebelum melanjutkan.
        </p>

        <button type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="rounded-2xl border border-rose-300 dark:border-rose-800 bg-rose-600 hover:bg-rose-500 text-white px-5 py-2.5 text-xs font-bold shadow-md shadow-rose-600/20 transition hover:scale-105 active:scale-95 whitespace-nowrap">
            Hapus Akun Saya
        </button>
    </div>

    <!-- Confirm Deletion Modal (Popup Card) -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 bg-white dark:bg-[#181f2c] text-slate-800 dark:text-white rounded-3xl border border-slate-200 dark:border-slate-700 shadow-2xl">
            @csrf
            @method('delete')

            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 shadow-inner">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <div class="text-center">
                <h3 class="text-lg font-black text-slate-900 dark:text-white">
                    Apakah Anda yakin ingin menghapus akun?
                </h3>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 leading-relaxed max-w-md mx-auto">
                    Setelah akun dihapus, semua sumber daya dan data tugas Anda akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan.
                </p>
            </div>

            <div class="mt-6">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5 text-left">
                    Password Konfirmasi
                </label>
                <input id="password" name="password" type="password"
                       class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-3 text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:border-rose-500 focus:bg-white dark:focus:bg-[#111827] focus:ring-2 focus:ring-rose-200 dark:focus:ring-rose-950 transition"
                       placeholder="Masukkan password Anda..." />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-left" />
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" x-on:click="$dispatch('close')"
                        class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    Batalkan
                </button>
                <button type="submit"
                        class="rounded-xl bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-rose-600/30 transition">
                    Hapus Akun Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
