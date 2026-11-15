<x-app-layout>
    <div class="min-h-screen bg-slate-100 dark:bg-[#0f172a] text-slate-800 dark:text-slate-100 transition-colors duration-300 py-6 sm:py-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Hero Identity Banner (Bespoke Glassmorphic & Glowing Mesh) -->
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950 p-6 sm:p-10 text-white shadow-2xl border border-white/10">
                <!-- Background Decorative Glowing Spheres -->
                <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-purple-500/20 blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    
                    <!-- Left: Avatar & User Identity -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                        <!-- Monogram Avatar with Glowing Ring & Pulse -->
                        <div class="relative">
                            <div class="flex h-20 w-20 sm:h-24 sm:w-24 shrink-0 items-center justify-center rounded-3xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 text-3xl sm:text-4xl font-black text-white shadow-xl shadow-indigo-500/30 ring-4 ring-white/15">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="absolute bottom-1 right-1 h-5 w-5 rounded-full bg-emerald-400 ring-4 ring-slate-900 shadow-sm" title="Online & Aktif"></span>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ Auth::user()->name }}</h1>
                                <span class="rounded-full bg-indigo-500/30 border border-indigo-400/40 px-2.5 py-0.5 text-xs font-bold text-indigo-200">
                                    ✨ Task Master
                                </span>
                            </div>
                            <p class="text-sm font-medium text-slate-300 flex items-center justify-center sm:justify-start gap-1.5">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                                <span>{{ Auth::user()->email }}</span>
                            </p>
                            <p class="text-xs text-slate-400 flex items-center justify-center sm:justify-start gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Bergabung sejak {{ Auth::user()->created_at?->translatedFormat('F Y') ?? 'Baru saja' }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right: Quick Navigation Badges -->
                    <div class="flex flex-wrap items-center justify-center gap-2 sm:justify-end">
                        <a href="{{ route('todos.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 px-4 py-2.5 text-xs font-bold text-white shadow-sm backdrop-blur transition hover:scale-105 active:scale-95">
                            <span>📋 Ke Todo Board</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 px-4 py-2.5 text-xs font-bold text-white shadow-sm backdrop-blur transition hover:scale-105 active:scale-95">
                            <span>📊 Dashboard</span>
                        </a>
                    </div>
                </div>

                <!-- Bento Stat Strip on Bottom of Hero -->
                <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4 border-t border-white/10 pt-6">
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-3.5 text-center">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Tugas</p>
                        <p class="mt-1 text-2xl font-black text-white">{{ $totalTodos }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-3.5 text-center">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-400">Selesai</p>
                        <p class="mt-1 text-2xl font-black text-emerald-400">{{ $completedTodos }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-3.5 text-center">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-amber-400">Aktif</p>
                        <p class="mt-1 text-2xl font-black text-amber-400">{{ $pendingTodos }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-3.5 text-center">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-400">Penyelesaian</p>
                        <p class="mt-1 text-2xl font-black text-indigo-300">{{ $progress }}%</p>
                    </div>
                </div>
            </section>

            <!-- 2. Main Settings Grid (Bento Style & Eye-Catching Cards) -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 items-start">
                
                <!-- Card 1: Informasi Profil Akun -->
                <div class="rounded-3xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-[#181f2c] p-6 sm:p-8 shadow-sm transition hover:shadow-md">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Card 2: Ubah Kata Sandi & Keamanan -->
                <div class="rounded-3xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-[#181f2c] p-6 sm:p-8 shadow-sm transition hover:shadow-md">
                    @include('profile.partials.update-password-form')
                </div>

            </div>

            <!-- 3. Zona Bahaya (Danger Zone Card) -->
            <div class="rounded-3xl border border-rose-200 dark:border-rose-900/60 bg-gradient-to-r from-rose-50/60 via-white to-rose-50/30 dark:from-rose-950/20 dark:via-[#181f2c] dark:to-rose-950/10 p-6 sm:p-8 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
