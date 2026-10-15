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
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50/40 to-purple-50 py-8 transition-colors dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 p-6 text-white shadow-xl shadow-indigo-200 sm:p-8">
                <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-sm font-medium text-indigo-100">Selamat datang kembali,</p>
                        <h1 class="mt-1 text-3xl font-black tracking-tight">{{ Auth::user()->name }} 👋</h1>
                        <p class="mt-3 max-w-lg text-sm leading-6 text-indigo-100">Atur prioritas, selesaikan tugas, dan jaga fokus Anda hari ini.</p>
                    </div>
                    <a href="{{ route('todos.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-700 shadow-lg transition hover:bg-indigo-50">Buka Todo List <span class="ml-2">→</span></a>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm dark:border-blue-900 dark:bg-gray-900"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total tugas</p><p class="mt-2 text-3xl font-black text-blue-600">{{ $total }}</p></div>
                <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-emerald-900 dark:bg-gray-900"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</p><p class="mt-2 text-3xl font-black text-emerald-600">{{ $completed }}</p></div>
                <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm dark:border-amber-900 dark:bg-gray-900"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perlu dikerjakan</p><p class="mt-2 text-3xl font-black text-amber-600">{{ $pending }}</p></div>
            </section>

            <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between"><div><h3 class="font-bold text-gray-900 dark:text-white">Progress tugas</h3><p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $completed }} dari {{ $total }} tugas selesai</p></div><span class="text-2xl font-black text-indigo-600">{{ $progress }}%</span></div>
                <div class="mt-5 h-3 overflow-hidden rounded-full bg-gray-100"><div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 transition-all" style="width: {{ $progress }}%"></div></div>
            </section>
        </div>
    </div>
</x-app-layout>
