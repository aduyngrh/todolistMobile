<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Overview</p>
                <h2 class="mt-1 text-2xl font-black tracking-tight text-gray-900 dark:text-white">Dashboard</h2>
            </div>
            <span class="hidden rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 sm:inline-flex">My Task Board</span>
        </div>
    </x-slot>

    @php
        $todos = \App\Models\Todo::where('user_id', Auth::id())->get();
        $total = $todos->count();
        $completed = $todos->where('completed', true)->count();
        $pending = $total - $completed;
        $progress = $total ? round(($completed / $total) * 100) : 0;
        $overdue = $todos->filter(fn ($todo) => ! $todo->completed && $todo->due_date?->isPast() && ! $todo->due_date->isToday())->count();
        $highPriority = $todos->where('priority', 'High')->where('completed', false)->count();
        $weekCompleted = $todos->filter(fn ($todo) => $todo->completed && $todo->updated_at->isAfter(now()->startOfWeek()))->count();
        $topCategory = $todos->groupBy('category')->sortByDesc(fn ($items) => $items->count())->keys()->first() ?? 'Belum ada';
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/40 py-8 transition-colors duration-300 dark:from-gray-950 dark:via-gray-950 dark:to-slate-900 sm:py-12">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-blue-100/80 bg-white/90 p-5 shadow-sm shadow-blue-100/50 backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:shadow-md dark:border-blue-900/60 dark:bg-gray-900/90 dark:shadow-none"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total tugas</p><p class="mt-2 text-3xl font-black tracking-tight text-blue-600">{{ $total }}</p></div>
                <div class="rounded-2xl border border-emerald-100/80 bg-white/90 p-5 shadow-sm shadow-emerald-100/50 backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:shadow-md dark:border-emerald-900/60 dark:bg-gray-900/90 dark:shadow-none"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</p><p class="mt-2 text-3xl font-black tracking-tight text-emerald-600">{{ $completed }}</p></div>
                <div class="rounded-2xl border border-amber-100/80 bg-white/90 p-5 shadow-sm shadow-amber-100/50 backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:shadow-md dark:border-amber-900/60 dark:bg-gray-900/90 dark:shadow-none"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perlu dikerjakan</p><p class="mt-2 text-3xl font-black tracking-tight text-amber-600">{{ $pending }}</p></div>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white/90 p-6 shadow-sm shadow-slate-200/60 backdrop-blur dark:border-gray-800 dark:bg-gray-900/90 dark:shadow-none">
                <div class="flex items-center justify-between"><div><h3 class="font-bold text-gray-900 dark:text-white">Progress tugas</h3><p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $completed }} dari {{ $total }} tugas selesai</p></div><span class="text-2xl font-black tracking-tight text-indigo-600">{{ $progress }}%</span></div>
                <div class="mt-5 h-2.5 overflow-hidden rounded-full bg-slate-100 dark:bg-gray-800"><div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500 ease-out" style="width: {{ $progress }}%"></div></div>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white/90 p-6 shadow-sm shadow-slate-200/60 backdrop-blur dark:border-gray-800 dark:bg-gray-900/90 dark:shadow-none">
                <div class="mb-5 flex items-center gap-3"><span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-900/70 dark:text-indigo-300"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 16v-4m4 4V8m4 8v-7"/></svg></span><div><h3 class="font-bold text-gray-900 dark:text-white">Dashboard insight</h3><p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan kondisi tugas Anda</p></div></div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-rose-50/80 p-4 transition-colors duration-300 hover:bg-rose-100/80 dark:bg-rose-950/20 dark:hover:bg-rose-950/40"><p class="text-xs font-semibold text-rose-700 dark:text-rose-300">Terlambat</p><p class="mt-1 text-2xl font-black text-rose-700 dark:text-rose-300">{{ $overdue }}</p><p class="text-xs text-rose-600/80 dark:text-rose-300/70">Tugas melewati deadline</p></div>
                    <div class="rounded-xl bg-amber-50/80 p-4 transition-colors duration-300 hover:bg-amber-100/80 dark:bg-amber-950/20 dark:hover:bg-amber-950/40"><p class="text-xs font-semibold text-amber-700 dark:text-amber-300">Prioritas tinggi</p><p class="mt-1 text-2xl font-black text-amber-700 dark:text-amber-300">{{ $highPriority }}</p><p class="text-xs text-amber-600/80 dark:text-amber-300/70">Belum selesai</p></div>
                    <div class="rounded-xl bg-emerald-50/80 p-4 transition-colors duration-300 hover:bg-emerald-100/80 dark:bg-emerald-950/20 dark:hover:bg-emerald-950/40"><p class="text-xs font-semibold text-emerald-700 dark:text-emerald-300">Selesai minggu ini</p><p class="mt-1 text-2xl font-black text-emerald-700 dark:text-emerald-300">{{ $weekCompleted }}</p><p class="text-xs text-emerald-600/80 dark:text-emerald-300/70">Produktivitas terbaru</p></div>
                    <div class="rounded-xl bg-indigo-50/80 p-4 transition-colors duration-300 hover:bg-indigo-100/80 dark:bg-indigo-950/20 dark:hover:bg-indigo-950/40"><p class="text-xs font-semibold text-indigo-700 dark:text-indigo-300">Kategori utama</p><p class="mt-1 truncate text-2xl font-black text-indigo-700 dark:text-indigo-300">{{ $topCategory }}</p><p class="text-xs text-indigo-600/80 dark:text-indigo-300/70">Paling sering digunakan</p></div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
