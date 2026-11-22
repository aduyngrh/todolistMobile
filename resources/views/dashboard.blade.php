<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Overview</p>
                <h2 class="mt-1 text-2xl font-black tracking-tight text-gray-900 dark:text-white">Dashboard</h2>
            </div>
            <a href="{{ route('todos.index') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-indigo-700">Buka Todo List</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-100 py-8 dark:bg-[#0f172a] sm:py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950 p-6 text-white shadow-2xl sm:p-10">
                <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-indigo-500/20 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-purple-500/20 blur-3xl"></div>
                <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-col items-center gap-5 text-center sm:flex-row sm:items-start sm:text-left">
                        <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-3xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 text-3xl font-black text-white shadow-xl shadow-indigo-500/30 ring-4 ring-white/15 sm:h-24 sm:w-24 sm:text-4xl">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center justify-center gap-2 sm:justify-start"><h1 class="text-2xl font-black tracking-tight sm:text-3xl">{{ $user->name }}</h1><span class="rounded-full border border-indigo-400/40 bg-indigo-500/30 px-2.5 py-0.5 text-xs font-bold text-indigo-200">? Task Master</span></div>
                            <p class="text-sm font-medium text-slate-300">{{ $user->email }}</p>
                            <p class="text-xs text-slate-400">Bergabung sejak {{ $user->created_at?->translatedFormat('F Y') ?? 'Baru saja' }}</p>
                        </div>
                    </div>
                    <a href="#account-settings" class="rounded-2xl border border-white/15 bg-white/10 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-white/20">Pengaturan akun</a>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-6 lg:grid-cols-2" id="account-settings">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-[#181f2c] sm:p-8">@include('profile.partials.update-profile-information-form')</div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-[#181f2c] sm:p-8">@include('profile.partials.update-password-form')</div>
            </section>

            <section class="space-y-4">
                <div class="flex items-end justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Todo Board</p><h2 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">Card tugas Anda</h2></div><a href="{{ route('todos.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">Lihat semua ?</a></div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($todos->groupBy('category')->take(6) as $category => $categoryTodos)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-[#181f2c]"><div class="flex items-center justify-between gap-3"><h3 class="font-bold text-slate-900 dark:text-white">{{ $category }}</h3><span class="rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">{{ $categoryTodos->count() }}</span></div><div class="mt-4 flex flex-col gap-2">@foreach($categoryTodos->take(3) as $todo)<div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-2.5 dark:border-slate-700 dark:bg-slate-800"><p class="truncate text-sm font-semibold {{ $todo->completed ? 'text-slate-400 line-through' : 'text-slate-800 dark:text-slate-100' }}">{{ $todo->title }}</p><div class="mt-1 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400"><span>{{ $todo->priority }}</span><span>{{ $todo->due_date?->format('d M') ?? 'Tanpa deadline' }}</span></div></div>@endforeach</div></div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 dark:border-slate-700 dark:bg-[#181f2c] dark:text-slate-400">Belum ada card tugas.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
