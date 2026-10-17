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

    <div class="min-h-screen bg-slate-50 py-8 transition-colors dark:bg-gray-950 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-3xl bg-slate-800 p-6 text-white shadow-xl shadow-slate-300 sm:p-8">
                <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-sm font-medium text-indigo-100">Selamat datang kembali,</p>
                        <h1 class="mt-1 flex items-center gap-3 text-3xl font-black tracking-tight">{{ Auth::user()->name }} ✨</h1>
                        <p class="mt-3 max-w-lg text-sm leading-6 text-indigo-100">Atur prioritas, selesaikan tugas, dan jaga fokus Anda hari ini.</p>
                    </div>
                    <a href="{{ route('todos.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-700 shadow-lg transition hover:bg-indigo-50">Buka Todo List <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg></a>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm dark:border-blue-900 dark:bg-gray-900"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total tugas</p><p class="mt-2 text-3xl font-black text-blue-600">{{ $total }}</p></div>
                <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-emerald-900 dark:bg-gray-900"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</p><p class="mt-2 text-3xl font-black text-emerald-600">{{ $completed }}</p></div>
                <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm dark:border-amber-900 dark:bg-gray-900"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perlu dikerjakan</p><p class="mt-2 text-3xl font-black text-amber-600">{{ $pending }}</p></div>
            </section>

            <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between"><div><h3 class="font-bold text-gray-900 dark:text-white">Progress tugas</h3><p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $completed }} dari {{ $total }} tugas selesai</p></div><span class="text-2xl font-black text-indigo-600">{{ $progress }}%</span></div>
                <div class="mt-5 h-3 overflow-hidden rounded-full bg-gray-700"><div class="h-full rounded-full bg-slate-300 transition-all" style="width: {{ $progress }}%"></div></div>
            </section>

            <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="mb-5 flex items-center gap-3"><span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 16v-4m4 4V8m4 8v-7"/></svg></span><div><h3 class="font-bold text-gray-900 dark:text-white">Dashboard insight</h3><p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan kondisi tugas Anda</p></div></div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-rose-50 p-4 dark:bg-rose-950/30"><p class="text-xs font-semibold text-rose-700 dark:text-rose-300">Terlambat</p><p class="mt-1 text-2xl font-black text-rose-700 dark:text-rose-300">{{ $overdue }}</p><p class="text-xs text-rose-600/80">Tugas melewati deadline</p></div>
                    <div class="rounded-xl bg-amber-50 p-4 dark:bg-amber-950/30"><p class="text-xs font-semibold text-amber-700 dark:text-amber-300">Prioritas tinggi</p><p class="mt-1 text-2xl font-black text-amber-700 dark:text-amber-300">{{ $highPriority }}</p><p class="text-xs text-amber-600/80">Belum selesai</p></div>
                    <div class="rounded-xl bg-emerald-50 p-4 dark:bg-emerald-950/30"><p class="text-xs font-semibold text-emerald-700 dark:text-emerald-300">Selesai minggu ini</p><p class="mt-1 text-2xl font-black text-emerald-700 dark:text-emerald-300">{{ $weekCompleted }}</p><p class="text-xs text-emerald-600/80">Produktivitas terbaru</p></div>
                    <div class="rounded-xl bg-indigo-50 p-4 dark:bg-indigo-950/30"><p class="text-xs font-semibold text-indigo-700 dark:text-indigo-300">Kategori utama</p><p class="mt-1 truncate text-2xl font-black text-indigo-700 dark:text-indigo-300">{{ $topCategory }}</p><p class="text-xs text-indigo-600/80">Paling sering digunakan</p></div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
