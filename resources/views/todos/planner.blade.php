<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Rencana harian</p>
                <h2 class="mt-1 text-2xl font-black text-gray-900 dark:text-white">Daily Planner</h2>
            </div>
            <a href="{{ route('todos.index') }}" class="rounded-xl bg-slate-800 px-4 py-2 text-center text-sm font-bold text-white hover:bg-slate-900">Todo List</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8 dark:bg-gray-950 sm:py-12">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <a href="{{ route('todos.planner', ['date' => $plannerDate->copy()->subDay()->toDateString()]) }}" class="rounded-lg px-3 py-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800" aria-label="Hari sebelumnya">←</a>
                <div class="text-center">
                    <h3 class="font-black text-gray-900 dark:text-white">{{ $plannerDate->translatedFormat('l, d F Y') }}</h3>
                    <a href="{{ route('todos.planner') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 dark:text-slate-300">Hari ini</a>
                </div>
                <a href="{{ route('todos.planner', ['date' => $plannerDate->copy()->addDay()->toDateString()]) }}" class="rounded-lg px-3 py-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800" aria-label="Hari berikutnya">→</a>
            </div>

            @php
                $completed = $todos->where('completed', true)->count();
                $total = $todos->count();
                $progress = $total ? (int) round(($completed / $total) * 100) : 0;
            @endphp
            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between text-sm font-bold text-gray-700 dark:text-gray-200"><span>Progress hari ini</span><span>{{ $completed }}/{{ $total }} selesai</span></div>
                <div class="mt-3 h-3 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700"><div class="h-full rounded-full bg-slate-700 transition-all" style="width: {{ $progress }}%"></div></div>
            </section>

            <section class="space-y-3">
                @forelse($todos as $todo)
                    <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="h-3 w-3 rounded-full {{ $todo->completed ? 'bg-emerald-500' : ($todo->priority === 'High' ? 'bg-rose-500' : 'bg-slate-400') }}"></span>
                                    <h3 class="truncate font-bold {{ $todo->completed ? 'text-gray-400 line-through' : 'text-gray-900 dark:text-white' }}">{{ $todo->title }}</h3>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">{{ $todo->start_time ? substr($todo->start_time, 0, 5) : 'Tanpa jam' }}{{ $todo->end_time ? ' – '.substr($todo->end_time, 0, 5) : '' }} · {{ $todo->category }} · {{ $todo->priority }}</p>
                                @if($todo->reminder_at)
                                    <p class="mt-1 text-xs font-semibold text-slate-500">Pengingat: {{ $todo->reminder_at->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('todos.update', $todo) }}">@csrf @method('PATCH')
                                <button type="submit" class="rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-700 hover:text-white">{{ $todo->completed ? 'Buka lagi' : 'Tandai selesai' }}</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-gray-300 bg-white p-12 text-center text-sm font-semibold text-gray-500 dark:border-gray-700 dark:bg-gray-900">Belum ada tugas untuk tanggal ini.</div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>