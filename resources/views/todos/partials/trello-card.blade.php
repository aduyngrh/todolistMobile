@php
    $subtaskTotal = $todo->subtasks->count();
    $subtaskDone = $todo->subtasks->where('completed', true)->count();
    $subtaskProgress = $subtaskTotal ? (int) round(($subtaskDone / $subtaskTotal) * 100) : 0;
    $commentsCount = $todo->comments->count();
    
    // Check if description has an image URL
    $imageUrl = null;
    if ($todo->description && preg_match('/(https?:\/\/[^\s]+(?:\.jpg|\.jpeg|\.png|\.gif|\.webp)[^\s]*)/i', $todo->description, $matches)) {
        $imageUrl = $matches[1];
    }
@endphp

<div class="trello-card rounded-xl p-3 shadow-sm border border-slate-200/80 bg-white hover:bg-slate-50/90 dark:border-slate-700/60 dark:bg-[#242e42] dark:hover:bg-[#2b374e] transition-all cursor-grab active:cursor-grabbing group relative select-none"
     data-id="{{ $todo->id }}"
     data-title="{{ e($todo->title) }}"
     data-description="{{ e($todo->description ?? '') }}"
     data-category="{{ e($todo->category) }}"
     data-due-date="{{ $todo->due_date?->format('Y-m-d') }}"
     data-priority="{{ $todo->priority }}"
     data-start-time="{{ $todo->start_time }}"
     data-end-time="{{ $todo->end_time }}"
     data-reminder-at="{{ $todo->reminder_at?->format('Y-m-d\TH:i') }}"
     tabindex="0" aria-keyshortcuts="E D">

    <!-- Optional Image Attachment Preview -->
    @if($imageUrl)
        <div class="mb-2.5 overflow-hidden rounded-lg max-h-40 bg-slate-100 dark:bg-black/30">
            <img src="{{ $imageUrl }}" alt="Card preview" class="w-full h-auto object-cover rounded-lg">
        </div>
    @endif

    <!-- Card Title & Actions Row -->
    <div class="flex items-start justify-between gap-2">
        <h3 class="card-title text-[13.5px] font-semibold leading-snug text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-white transition flex-1">
            {{ $todo->title }}
        </h3>

        <!-- Card Action Buttons (Direct Quick Icons + Titik 3) -->
        <div class="flex items-center gap-0.5 shrink-0 -mr-1 -mt-0.5 no-drag">
            <!-- Edit Pencil Button (Direct Edit) -->
            <button type="button" onclick="handleCardEditClick(this)"
                    class="p-1 rounded-lg text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-700/60 transition no-drag"
                    title="Edit kartu">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3.536 3.536-6 6H9v-3.536z" />
                </svg>
            </button>

            <!-- Trash Delete Button (Direct Delete) -->
            <button type="button" onclick="handleCardDeleteClick(this)"
                    class="p-1 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition no-drag"
                    title="Hapus kartu">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>

            <!-- Titik 3 Button (Opens Card Options Modal) -->
            <button type="button" onclick="openCardOptionsModal(this)"
                    class="p-1 rounded-lg text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700/60 transition no-drag"
                    title="Menu opsi kartu">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Metadata Badges Row (Trello icons style) -->
    <div class="mt-2.5 flex flex-wrap items-center gap-2.5 text-slate-400 dark:text-slate-400 text-[11px]">
        
        <!-- Watching Eye Icon -->
        <span class="inline-flex items-center text-slate-400 dark:text-slate-400" title="Mengikuti kartu ini">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
            </svg>
        </span>

        <!-- Description Icon (3 Horizontal Lines) -->
        @if(!empty(trim($todo->description ?? '')))
            <span class="inline-flex items-center text-slate-400 dark:text-slate-400" title="Kartu memiliki deskripsi">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
            </span>
        @endif

        <!-- Comments Badge -->
        @if($commentsCount > 0)
            <span class="inline-flex items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition" title="{{ $commentsCount }} Komentar">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zm-4 0H9v2h2V9z" clip-rule="evenodd" />
                </svg>
                <span>{{ $commentsCount }}</span>
            </span>
        @endif

        <!-- Subtasks Checklist Badge -->
        @if($subtaskTotal > 0)
            <span class="inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-[10.5px] font-semibold {{ $subtaskDone === $subtaskTotal ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300' }}"
                  title="Subtasks: {{ $subtaskDone }} dari {{ $subtaskTotal }} selesai">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span>{{ $subtaskDone }}/{{ $subtaskTotal }}</span>
            </span>
        @endif

        <!-- Due Date Badge -->
        @if($todo->due_date)
            @php
                $isOverdue = !$todo->completed && $todo->due_date->isPast() && !$todo->due_date->isToday();
                $isToday = !$todo->completed && $todo->due_date->isToday();
            @endphp
            <span class="inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-[10.5px] font-bold {{ $todo->completed ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300' : ($isOverdue ? 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300' : ($isToday ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300')) }}"
                  title="Tenggat waktu: {{ $todo->due_date->format('d M Y') }}">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $todo->due_date->format('d M') }}</span>
            </span>
        @endif

        <!-- Priority Pill -->
        @if($todo->priority === 'High')
            <span class="rounded bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 px-1.5 py-0.5 text-[10px] font-bold uppercase">
                High
            </span>
        @elseif($todo->priority === 'Medium')
            <span class="rounded bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300 px-1.5 py-0.5 text-[10px] font-bold uppercase">
                Med
            </span>
        @endif
    </div>

    <!-- Expandable Subtasks & Comments Section inside Card -->
    <details class="mt-3 pt-2 border-t border-slate-100 dark:border-slate-700/60 group/details no-drag">
        <summary class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 cursor-pointer list-none py-0.5">
            <span class="flex items-center gap-1 font-medium">
                <span>Rincian & Aktivitas</span>
            </span>
            <span class="text-[9px] group-open/details:rotate-180 transition-transform">▼</span>
        </summary>

        <div class="mt-2 space-y-3 pt-1">
            <!-- Description text if present and no preview -->
            @if(!empty(trim($todo->description ?? '')) && !$imageUrl)
                <div class="rounded-lg bg-slate-50 dark:bg-slate-900/50 p-2.5 text-xs text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed border border-slate-200/60 dark:border-slate-700/40">
                    {{ $todo->description }}
                </div>
            @endif

            <!-- Subtasks management -->
            <div class="rounded-lg bg-slate-50 dark:bg-slate-900/40 p-2.5 border border-slate-200/60 dark:border-slate-700/40">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">Langkah Tugas</span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-bold">{{ $subtaskDone }}/{{ $subtaskTotal }}</span>
                </div>
                
                @if($subtaskTotal > 0)
                    <div class="mb-2 h-1.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                        <div class="h-full rounded-full bg-emerald-500 dark:bg-emerald-400 transition-all" style="width: {{ $subtaskProgress }}%"></div>
                    </div>
                @endif

                <div class="space-y-1.5">
                    @foreach($todo->subtasks as $subtask)
                        <div class="flex items-center gap-2 rounded bg-white dark:bg-[#1a2230] px-2.5 py-1.5 text-xs border border-slate-100 dark:border-slate-700/60">
                            <form method="POST" action="{{ route('subtasks.update', $subtask) }}" class="no-drag">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex h-4 w-4 items-center justify-center rounded border {{ $subtask->completed ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-400 hover:border-slate-600' }}">
                                    @if($subtask->completed)
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>
                                    @endif
                                </button>
                            </form>
                            <span class="flex-1 text-[11px] {{ $subtask->completed ? 'line-through text-slate-400' : 'text-slate-700 dark:text-slate-200' }}">
                                {{ $subtask->title }}
                            </span>
                            <form method="POST" action="{{ route('subtasks.destroy', $subtask) }}" class="no-drag">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-500 text-xs px-1">×</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Add subtask inline -->
                <form method="POST" action="{{ route('subtasks.store', $todo) }}" class="mt-2 flex gap-1.5 no-drag">
                    @csrf
                    <input name="title" required maxlength="255" placeholder="Tambah langkah..."
                           class="flex-1 rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-[#1e2736] px-2.5 py-1 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-0">
                    <button type="submit" class="rounded-lg bg-emerald-600 hover:bg-emerald-500 px-2 py-1 text-xs font-bold text-white transition">＋</button>
                </form>
            </div>

            <!-- Comment Thread Component -->
            <div class="rounded-lg bg-slate-50 dark:bg-slate-900/40 p-2.5 border border-slate-200/60 dark:border-slate-700/40 no-drag">
                <x-comment-thread :todo="$todo" />
            </div>
        </div>
    </details>
</div>
