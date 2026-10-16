<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 tracking-tight flex items-center gap-2 dark:text-white">
                    <span>✨</span> My Task Board
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Tarik dan lepas (drag & drop) tugas antar kolom untuk mengubah status.</p>
            </div>
            <div class="flex items-center gap-2 bg-white border border-gray-200/80 rounded-xl px-3.5 py-1.5 shadow-sm text-xs font-semibold text-gray-600">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span>{{ $todos->count() }} Total Tugas</span>
            </div>
        </div>
    </x-slot>

    <!-- Script SortableJS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-8 transition-colors dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Edit Modal -->
            <div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto backdrop-blur-sm bg-black/30">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                        <form id="edit-todo-form" method="POST">
                            @csrf @method('PATCH')
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 text-white">
                                <h3 class="text-lg font-bold">Edit Tugas ✨</h3>
                            </div>
                            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4 space-y-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Judul Tugas</label>
                                    <input type="text" id="edit-todo-title" name="title" required 
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Catatan / Sub-tugas</label>
                                    <textarea id="edit-todo-description" name="description" rows="3"
                                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                                              placeholder="Tambahkan catatan atau sub-tugas..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Kategori</label>
                                    <select id="edit-todo-category" name="category" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                                        <option value="Work">Work</option>
                                        <option value="Personal">Personal</option>
                                        <option value="Urgent">Urgent</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Tanggal Tenggat</label>
                                    <input type="date" id="edit-todo-due-date" name="due_date" 
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Prioritas</label>
                                    <select id="edit-todo-priority" name="priority" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2 border-t border-gray-100">
                                <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-base font-semibold text-white shadow-md hover:from-indigo-700 hover:to-purple-700 sm:w-auto sm:text-sm transition">
                                    Simpan
                                </button>
                                <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-200 px-5 py-2.5 bg-white text-base font-semibold text-gray-700 shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm transition">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Task Popup -->
            <div class="mb-8 flex justify-end">
                <button type="button" id="open-add-modal" class="rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:-translate-y-0.5">
                    + Tambah Tugas
                </button>
            </div>
            <div id="add-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
                <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-3xl bg-white p-5 shadow-2xl dark:bg-gray-900 sm:p-7">
                    <div class="mb-5 flex items-center justify-between">
                        <div><p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Task board</p><h3 class="mt-1 text-2xl font-black text-gray-900 dark:text-white">Tambah tugas baru</h3></div>
                        <button type="button" id="close-add-modal" class="rounded-xl px-3 py-2 text-2xl text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" aria-label="Tutup">×</button>
                    </div>
                <form method="POST" action="{{ route('todos.store') }}" class="flex flex-col gap-3">
                    @csrf
                    <div class="flex-1 relative">
                        <label for="todo-title" class="sr-only">Judul tugas</label>
                        <input id="todo-title" type="text" name="title" required autocomplete="off" maxlength="255"
                               class="w-full rounded-2xl border border-indigo-100 bg-indigo-50/50 px-5 py-3.5 text-sm font-medium text-gray-800 placeholder-indigo-400 transition-all focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200" 
                               placeholder="✨ Tambahkan rencana atau tugas baru...">
                        <span id="title-count" class="absolute bottom-1.5 right-4 text-[10px] text-gray-400">0/255</span>
                    </div>
                    <div class="flex-1 relative">
                        <label for="todo-description" class="sr-only">Catatan tambahan</label>
                        <textarea id="todo-description" name="description" rows="1" maxlength="1000"
                                  class="min-h-[52px] w-full resize-y rounded-2xl border border-purple-100 bg-purple-50/50 px-5 py-3.5 text-sm font-medium text-gray-800 placeholder-purple-400 transition-all focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200"
                                  placeholder="📝 Catatan tambahan / sub-tugas..."></textarea>
                        <span id="description-count" class="absolute bottom-1.5 right-4 text-[10px] text-gray-400">0/1000</span>
                    </div>
                    <div class="flex-1 relative">
                        <select name="category" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-3 py-2.5 text-sm text-gray-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                            <option value="Work">Work</option>
                            <option value="Personal" selected>Personal</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="flex-1 relative">
                        <input type="date" name="due_date" 
                               class="w-full bg-pink-50/50 border border-pink-100 rounded-2xl px-5 py-3.5 text-sm text-gray-800 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium">
                    </div>
                    <div class="flex-1 relative">
                        <select name="priority" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-3 py-2.5 text-sm text-gray-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                            <option value="High">High Priority</option>
                            <option value="Medium" selected>Medium Priority</option>
                            <option value="Low">Low Priority</option>
                        </select>
                    </div>
                    <button type="submit" class="mt-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:-translate-y-0.5">
                        Simpan Tugas
                    </button>
                </form>
                </div>
            </div>

            <!-- Drag & Drop Board -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                
                <!-- Column: To Do -->
                <div class="bg-gradient-to-b from-amber-50/80 to-orange-50/50 p-6 rounded-3xl border border-amber-200/60 shadow-xl shadow-amber-100/30">
                    <div class="flex justify-between items-center mb-5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3.5 h-3.5 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full shadow-sm shadow-amber-300"></span>
                            <h3 class="font-extrabold text-amber-900 text-sm tracking-wider uppercase">To Do / Pending</h3>
                        </div>
                        <span id="count-todo" class="bg-white border border-amber-200 text-amber-800 font-black text-xs px-3 py-1 rounded-full shadow-sm">
                            {{ $todos->where('completed', false)->count() }}
                        </span>
                    </div>

                    <!-- Draggable List -->
                    <div id="todo-list" class="space-y-4 min-h-[260px]" data-status="0">
                        @forelse ($todos->where('completed', false) as $todo)
                            <div class="task-card bg-white/90 backdrop-blur-sm p-5 rounded-2xl shadow-md shadow-amber-50 border border-amber-100 hover:shadow-xl hover:border-amber-300 transition-all duration-200 cursor-grab active:cursor-grabbing group" 
                                 data-id="{{ $todo->id }}">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-bold text-gray-800 leading-snug group-hover:text-indigo-600 transition">{{ $todo->title }}</span>
                                        <div class="flex gap-1 flex-wrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $todo->category === 'Urgent' ? 'bg-rose-100 text-rose-700' : ($todo->category === 'Work' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">{{ $todo->category }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $todo->priority === 'High' ? 'bg-red-100 text-red-700' : ($todo->priority === 'Low' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-100 text-yellow-700') }}">{{ $todo->priority }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 opacity-100 transition-opacity">
                                        <!-- Complete Icon -->
                                        <form method="POST" action="{{ route('todos.update', $todo) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-600 hover:text-white hover:shadow-md dark:border-emerald-800 dark:bg-emerald-950/40" title="Tandai selesai" aria-label="Tandai selesai">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                        <!-- Edit Icon -->
                                        <button type="button" class="edit-button p-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg transition" title="Edit"
                                                data-id="{{ $todo->id }}" data-title="{{ $todo->title }}" data-description="{{ $todo->description }}" data-category="{{ $todo->category }}" data-due-date="{{ $todo->due_date?->format('Y-m-d') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3.536 3.536-6 6H9v-3.536z" />
                                            </svg>
                                        </button>
                                        <!-- Delete Icon -->
                                        <form method="POST" action="{{ route('todos.destroy', $todo) }}" class="inline delete-todo-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                        <!-- Archive Icon -->
                                        <form method="POST" action="{{ route('todos.archive', $todo) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-slate-600 hover:bg-slate-600 hover:text-white rounded-lg transition" title="Arsipkan tugas" aria-label="Arsipkan tugas"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M6 7l1 12h10l1-12M9 7V5h6v2"/></svg></button>
                                        </form>
                                    </div>
                                </div>
                                @if($todo->description)
                                    <div class="text-xs text-indigo-900 bg-indigo-50/70 p-3 rounded-xl border border-indigo-100/60 mb-3 whitespace-pre-line font-medium">
                                        💡 {{ $todo->description }}
                                    </div>
                                @endif
                                <div class="mb-3 rounded-2xl border border-indigo-100 bg-indigo-50/50 p-4 dark:border-indigo-900 dark:bg-gray-800">
                                    <div class="mb-3 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700" aria-hidden="true"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h12M8 12h12M8 18h12M4 6h.01M4 12h.01M4 18h.01"/></svg></span>
                                            <div>
                                                <p class="text-xs font-extrabold uppercase tracking-wide text-indigo-700 dark:text-indigo-300">Langkah tugas</p>
                                                <p class="text-[11px] text-gray-500">Pecah tugas ini menjadi langkah kecil</p>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-white px-2.5 py-1 text-xs font-extrabold text-indigo-700 shadow-sm dark:bg-gray-900 dark:text-indigo-300">{{ $todo->subtasks->where('completed', true)->count() }}/{{ $todo->subtasks->count() }}</span>
                                    </div>
                                    @forelse($todo->subtasks as $subtask)
                                        <div class="mb-2 flex items-center gap-2 rounded-xl bg-white px-3 py-2 text-sm shadow-sm dark:bg-gray-900">
                                            <form method="POST" action="{{ route('subtasks.update', $subtask) }}">@csrf @method('PATCH')
                                                <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-lg border {{ $subtask->completed ? 'border-emerald-300 bg-emerald-100 text-emerald-700' : 'border-gray-300 bg-gray-50 text-gray-400' }}" title="{{ $subtask->completed ? 'Tandai belum selesai' : 'Tandai selesai' }}" aria-label="{{ $subtask->completed ? 'Tandai belum selesai' : 'Tandai selesai' }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg></button>
                                            </form>
                                            <span class="flex-1 {{ $subtask->completed ? 'line-through text-gray-400' : 'font-medium text-gray-700 dark:text-gray-200' }}">{{ $subtask->title }}</span>
                                            <form method="POST" action="{{ route('subtasks.destroy', $subtask) }}">@csrf @method('DELETE')
                                                <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 hover:bg-rose-100 hover:text-rose-600" title="Hapus subtask" aria-label="Hapus subtask"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/></svg></button>
                                            </form>
                                        </div>
                                    @empty
                                        <p class="rounded-xl border border-dashed border-indigo-200 px-3 py-2 text-xs text-gray-500">Belum ada langkah. Tambahkan langkah pertama di bawah.</p>
                                    @endforelse
                                    <form method="POST" action="{{ route('subtasks.store', $todo) }}" class="mt-3 flex gap-2">@csrf
                                        <label for="subtask-{{ $todo->id }}" class="sr-only">Nama langkah tugas</label>
                                        <input id="subtask-{{ $todo->id }}" name="title" required maxlength="255" placeholder="Contoh: Kumpulkan bahan..." class="min-w-0 flex-1 rounded-xl border-gray-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-indigo-200" title="Tulis langkah kecil untuk tugas ini">
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white hover:bg-indigo-700" title="Tambah langkah" aria-label="Tambah langkah"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg> Tambah</button>
                                    </form>
                                </div>
                                <div class="flex items-center justify-between pt-2.5 border-t border-gray-100 text-[11px] text-gray-400 font-semibold">
                                    <span>📅 {{ $todo->created_at->format('d M Y') }}</span>
                                    @if($todo->due_date)
                                        @php
                                            $isOverdue = !$todo->completed && $todo->due_date->isPast() && !$todo->due_date->isToday();
                                            $isSoon = !$todo->completed && $todo->due_date->isToday() || (!$todo->completed && $todo->due_date->isFuture() && $todo->due_date->diffInDays(now()) <= 1);
                                        @endphp
                                        <span class="px-2 py-0.5 rounded font-bold {{ $isOverdue ? 'bg-rose-100 text-rose-700 animate-pulse' : ($isSoon ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                            ⏰ {{ $todo->due_date->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state text-center py-12 border-2 border-dashed border-amber-300/60 rounded-2xl text-amber-700/60 text-xs font-semibold bg-amber-50/30">
                                📌 Belum ada tugas. Yuk buat baru!
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Column: Done -->
                <div class="bg-gradient-to-b from-emerald-50/80 to-teal-50/50 p-6 rounded-3xl border border-emerald-200/60 shadow-xl shadow-emerald-100/30">
                    <div class="flex justify-between items-center mb-5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3.5 h-3.5 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full shadow-sm shadow-emerald-300"></span>
                            <h3 class="font-extrabold text-emerald-900 text-sm tracking-wider uppercase">Done / Selesai</h3>
                        </div>
                        <span id="count-done" class="bg-white border border-emerald-200 text-emerald-800 font-black text-xs px-3 py-1 rounded-full shadow-sm">
                            {{ $todos->where('completed', true)->count() }}
                        </span>
                    </div>

                    <!-- Draggable List -->
                    <div id="done-list" class="space-y-4 min-h-[260px]" data-status="1">
                        @forelse ($todos->where('completed', true) as $todo)
                            <div class="task-card bg-white/70 backdrop-blur-sm p-5 rounded-2xl shadow-sm border border-emerald-100/60 hover:bg-white hover:shadow-md transition-all duration-200 cursor-grab active:cursor-grabbing group opacity-90 hover:opacity-100" 
                                 data-id="{{ $todo->id }}">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-semibold text-gray-500 line-through leading-snug">{{ $todo->title }}</span>
                                        @if($todo->description)
                                            <p class="text-xs text-gray-500 whitespace-pre-line">{{ $todo->description }}</p>
                                        @endif
                                        <div class="flex gap-1">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">{{ $todo->category }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">{{ $todo->priority }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 opacity-100 transition-opacity">
                                        <!-- Undo Icon -->
                                        <form method="POST" action="{{ route('todos.update', $todo) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-amber-200 bg-amber-50 text-amber-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-amber-500 hover:text-white hover:shadow-md dark:border-amber-800 dark:bg-amber-950/40" title="Batalkan selesai" aria-label="Batalkan selesai">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9" />
                                                </svg>
                                            </button>
                                        </form>
                                        <!-- Edit Icon -->
                                        <button type="button" class="edit-button p-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg transition" title="Edit"
                                                data-id="{{ $todo->id }}" data-title="{{ $todo->title }}" data-description="{{ $todo->description }}" data-category="{{ $todo->category }}" data-due-date="{{ $todo->due_date?->format('Y-m-d') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3.536 3.536-6 6H9v-3.536z" />
                                            </svg>
                                        </button>
                                        <!-- Delete Icon -->
                                        <form method="POST" action="{{ route('todos.destroy', $todo) }}" class="inline delete-todo-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @if($todo->description)
                                    <div class="text-xs text-gray-500 bg-gray-50/70 p-3 rounded-xl border border-gray-100 mb-3 whitespace-pre-line line-through">
                                        💡 {{ $todo->description }}
                                    </div>
                                @endif
                                <div class="flex items-center justify-between pt-2.5 border-t border-gray-100 text-[11px] text-gray-400 font-semibold">
                                    <span>📅 {{ $todo->created_at->format('d M Y') }}</span>
                                    <span class="text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-md font-bold">✓ Selesai</span>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state text-center py-12 border-2 border-dashed border-emerald-300/60 rounded-2xl text-emerald-700/60 text-xs font-semibold bg-emerald-50/30">
                                🎉 Belum ada tugas selesai.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Archived -->
                @if($archivedTodos->isNotEmpty())
                    <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-100 p-6 shadow-xl dark:border-slate-700 dark:bg-gray-800">
                        <div class="mb-5 flex items-center justify-between">
                            <h3 class="font-extrabold text-slate-800 dark:text-white">Arsip</h3>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-600">{{ $archivedTodos->count() }}</span>
                        </div>
                        <div class="space-y-3">
                            @foreach($archivedTodos as $todo)
                                <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm dark:bg-gray-900">
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $todo->title }}</p>
                                        <p class="text-xs text-gray-500">Diarsipkan {{ $todo->archived_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('todos.restore', $todo) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="rounded-xl bg-indigo-100 px-3 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-600 hover:text-white">Pulihkan</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Custom Delete Confirmation Dialog -->
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pindahkan tugas ke sampah?</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tugas bisa dipulihkan nanti dari database.</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="cancel-delete" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 dark:border-gray-700 dark:text-gray-200">Batalkan</button>
                <button type="button" id="confirm-delete" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Hapus tugas</button>
            </div>
        </div>
    </div>

    <!-- Script Drag & Drop Auto Update Status -->
    <script>
        function openEditModal(id, title, description, category, dueDate, priority) {
            const modal = document.getElementById('edit-modal');
            const form = document.getElementById('edit-todo-form');
            const inputTitle = document.getElementById('edit-todo-title');
            const textareaDesc = document.getElementById('edit-todo-description');
            const selectCategory = document.getElementById('edit-todo-category');
            const inputDueDate = document.getElementById('edit-todo-due-date');
            const selectPriority = document.getElementById('edit-todo-priority');
            
            form.action = `/todos/${id}`;
            inputTitle.value = title;
            textareaDesc.value = description || '';
            selectCategory.value = category || 'Personal';
            inputDueDate.value = dueDate || '';
            selectPriority.value = priority || 'Medium';
            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const updateCounter = (input, counter) => {
                document.getElementById(counter).textContent = `${input.value.length}/${input.maxLength}`;
            };
            document.getElementById('todo-title').addEventListener('input', (event) => updateCounter(event.target, 'title-count'));
            document.getElementById('todo-description').addEventListener('input', (event) => updateCounter(event.target, 'description-count'));

            const addModal = document.getElementById('add-modal');
            document.getElementById('open-add-modal').addEventListener('click', () => {
                addModal.classList.remove('hidden');
                addModal.classList.add('flex');
                document.getElementById('todo-title').focus();
            });
            const closeAddModal = () => {
                addModal.classList.add('hidden');
                addModal.classList.remove('flex');
            };
            document.getElementById('close-add-modal').addEventListener('click', closeAddModal);
            addModal.addEventListener('click', (event) => {
                if (event.target === addModal) closeAddModal();
            });

            document.querySelectorAll('.edit-button').forEach((button) => {
                button.addEventListener('click', () => openEditModal(
                    button.dataset.id,
                    button.dataset.title,
                    button.dataset.description,
                    button.dataset.category,
                    button.dataset.dueDate,
                    button.dataset.priority,
                ));
            });

            const deleteModal = document.getElementById('delete-modal');
            let deleteForm;
            document.querySelectorAll('.delete-todo-form').forEach((form) => form.addEventListener('submit', (event) => {
                event.preventDefault();
                deleteForm = form;
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
            }));
            document.getElementById('cancel-delete').addEventListener('click', () => {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
            });
            document.getElementById('confirm-delete').addEventListener('click', () => deleteForm?.submit());

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function setupSortable(el, targetStatus) {
                new Sortable(el, {
                    group: 'shared-tasks',
                    animation: 200,
                    ghostClass: 'opacity-40',
                    onAdd: function (evt) {
                        const taskId = evt.item.getAttribute('data-id');

                        // Kirim permintaan update status via fetch PATCH
                        fetch(`/todos/${taskId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-HTTP-Method-Override': 'PATCH'
                            },
                            body: JSON.stringify({ _method: 'PATCH' })
                        }).then(() => {
                            window.location.reload();
                        }).catch(err => console.error(err));
                    }
                });
            }

            setupSortable(document.getElementById('todo-list'), 0);
            setupSortable(document.getElementById('done-list'), 1);
        });
    </script>
</x-app-layout>