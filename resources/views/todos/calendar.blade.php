<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Perencanaan</p>
                <h2 class="mt-1 text-2xl font-black text-gray-900 dark:text-white">Calendar View</h2>
            </div>
            <a href="{{ route('todos.index') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-center text-sm font-bold text-white hover:bg-indigo-700">Todo List</a>
            <a href="{{ route('todos.planner') }}" class="rounded-xl bg-slate-800 px-4 py-2 text-center text-sm font-bold text-white hover:bg-slate-900">Daily Planner</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-8 transition-colors dark:bg-gray-950 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-5 flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <a href="{{ route('todos.calendar', ['month' => $monthDate->copy()->subMonth()->format('Y-m')]) }}" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800" aria-label="Bulan sebelumnya">←</a>
                <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $monthDate->translatedFormat('F Y') }}</h3>
                <a href="{{ route('todos.calendar', ['month' => $monthDate->copy()->addMonth()->format('Y-m')]) }}" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800" aria-label="Bulan berikutnya">→</a>
            </div>

            <div class="grid grid-cols-7 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                    <div class="border-b border-gray-200 bg-gray-50 p-2 text-center text-xs font-bold text-gray-500 dark:border-gray-800 dark:bg-gray-800">{{ $day }}</div>
                @endforeach
                @for($blank = 1; $blank < $monthDate->isoWeekday(); $blank++)
                    <div class="min-h-28 border-b border-r border-gray-100 bg-gray-50/50 dark:border-gray-800 dark:bg-gray-950/30"></div>
                @endfor
                @for($day = 1; $day <= $monthDate->daysInMonth; $day++)
                    @php $date = $monthDate->copy()->day($day); $dateKey = $date->format('Y-m-d'); @endphp
                    <div class="min-h-28 border-b border-r border-gray-100 p-2 dark:border-gray-800">
                        <span class="text-xs font-bold {{ $date->isToday() ? 'inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-white' : 'text-gray-500' }}">{{ $day }}</span>
                        <div class="mt-2 space-y-1">
                            @foreach($todos->get($dateKey, collect()) as $todo)
                                <a href="{{ route('todos.index') }}" class="block truncate rounded-lg px-2 py-1 text-[11px] font-semibold {{ $todo->completed ? 'bg-emerald-100 text-emerald-700 line-through' : ($todo->priority === 'High' ? 'bg-rose-100 text-rose-700' : 'bg-indigo-100 text-indigo-700') }}" title="{{ $todo->title }}">{{ $todo->title }}</a>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</x-app-layout>
