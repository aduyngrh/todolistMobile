<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Pengaturan akun</p>
            <h2 class="mt-1 text-2xl font-black tracking-tight text-gray-900 dark:text-white">Profil Saya</h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8 transition-colors dark:bg-gray-950 sm:py-12">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-3xl bg-slate-800 p-6 text-white shadow-xl shadow-slate-200/70 dark:shadow-black/20 sm:p-8">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-2xl font-black ring-1 ring-white/20">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm text-slate-300">Selamat datang kembali</p>
                        <h3 class="mt-1 text-2xl font-black">{{ Auth::user()->name }}</h3>
                        <p class="mt-1 text-sm text-slate-300">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </section>

            <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            </div>

            <div class="rounded-3xl border border-rose-100 bg-rose-50/70 p-5 shadow-sm dark:border-rose-900/50 dark:bg-rose-950/20 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
