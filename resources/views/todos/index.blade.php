<x-app-layout>
    <style>
        /* Kanban Custom Scrollbars */
        .kanban-scrollbar::-webkit-scrollbar {
            height: 10px;
            width: 8px;
        }
        .kanban-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }
        .dark .kanban-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .kanban-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        .dark .kanban-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
        }
        .kanban-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.35);
        }
        .dark .kanban-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .list-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .list-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .list-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 5px;
        }
        .dark .list-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
        }
    </style>

    <!-- Balanced Page Background (Consistent with other pages: slate neutral, not too dark, not too bright) -->
    <div class="min-h-screen bg-slate-100 dark:bg-[#111827] text-slate-900 dark:text-slate-100 transition-colors duration-300 flex flex-col">

        <!-- Board Top Bar -->
        <header class="sticky top-16 z-30 border-b border-slate-200/90 dark:border-slate-800 bg-white/80 dark:bg-[#172033]/90 backdrop-blur-md px-4 py-3 sm:px-6 shadow-sm">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                
                <!-- Left: Title & Count -->
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 text-lg shadow-sm">📋</span>
                    <div>
                        <h1 class="text-lg font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                            <span>My Task Board</span>
                            <span class="rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 px-2 py-0.5 text-[11px] font-bold">
                                {{ $todos->count() }} Tugas
                            </span>
                        </h1>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 hidden sm:block">Papan kategori tugas dengan drag & drop dan manajemen list.</p>
                    </div>
                </div>

            </div>
        </header>

        <!-- Main Kanban Board Container (Category Lists Only) -->
        <main class="flex-1 p-4 sm:p-6 overflow-x-auto kanban-scrollbar">
            <div id="board-columns-container" class="flex items-start gap-4 pb-4 select-none min-w-max">

                <!-- Render Server-Side Categories -->
                @foreach($categories as $categoryName)
                    @php
                        $categoryTodos = $todoItems->filter(fn($t) => strcasecmp($t->category, $categoryName) === 0);
                    @endphp
                    <div class="trello-column w-72 sm:w-80 flex-shrink-0 rounded-2xl p-3 shadow-md border border-slate-200 dark:border-slate-800/80 bg-slate-200/80 dark:bg-[#181f2c] flex flex-col max-h-[calc(100vh-170px)]"
                         data-category="{{ $categoryName }}" data-custom-list="false">
                        
                        <!-- Column Header -->
                        <div class="column-drag-handle flex items-center justify-between px-2 py-1.5 mb-2 cursor-grab active:cursor-grabbing">
                            <div class="flex items-center gap-2 truncate">
                                <h2 class="column-name text-sm font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-100 truncate">
                                    {{ $categoryName }}
                                </h2>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-white/70 dark:bg-slate-800 px-2 py-0.5 rounded-md count-badge border border-slate-200/60 dark:border-slate-700">
                                    {{ $categoryTodos->count() }}
                                </span>
                                
                                <!-- Column Titik 3 Button (List Menu) -->
                                <div class="relative inline-block text-left">
                                    <button type="button" onclick="toggleColumnMenu(event, this)"
                                            class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-300/60 dark:hover:bg-slate-700/60 rounded-lg p-1 transition" title="Menu list">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/></svg>
                                    </button>
                                    
                                    <!-- Column Dropdown Menu -->
                                    <div class="column-menu-dropdown hidden absolute right-0 z-40 mt-1 w-44 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl dark:border-slate-700 dark:bg-slate-800 text-xs">
                                        <button type="button" onclick="openRenameListModal('{{ addslashes($categoryName) }}')"
                                                class="flex w-full items-center gap-2 rounded-lg px-2.5 py-1.5 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/70 transition">
                                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit Nama List</span>
                                        </button>
                                        <button type="button" onclick="quickAddCard('{{ addslashes($categoryName) }}')"
                                                class="flex w-full items-center gap-2 rounded-lg px-2.5 py-1.5 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/70 transition">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                            <span>Tambah Kartu</span>
                                        </button>
                                        <div class="my-1 border-t border-slate-100 dark:border-slate-700"></div>
                                        <button type="button" onclick="confirmDeleteList('{{ addslashes($categoryName) }}')"
                                                class="flex w-full items-center gap-2 rounded-lg px-2.5 py-1.5 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/40 transition">
                                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus List</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Draggable Cards List in Category -->
                        <div class="sortable-list list-scrollbar flex-1 overflow-y-auto space-y-2.5 px-0.5 py-1 min-h-[60px]"
                             data-column-type="category" data-column-val="{{ $categoryName }}">
                            @foreach($categoryTodos as $todo)
                                @include('todos.partials.todo-card', ['todo' => $todo])
                            @endforeach
                            <div class="empty-placeholder text-center py-8 text-xs text-slate-400 dark:text-slate-500 border border-dashed border-slate-300 dark:border-slate-700/60 rounded-xl {{ $categoryTodos->isEmpty() ? '' : 'hidden' }}">
                                Belum ada kartu di list ini.
                            </div>
                        </div>

                        <!-- Column Footer -->
                        <div class="pt-2 mt-2 border-t border-slate-300/60 dark:border-slate-700/60 flex items-center justify-between">
                            <button type="button" onclick="quickAddCard('{{ addslashes($categoryName) }}')"
                                    class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-white/60 dark:hover:bg-slate-700/50 px-3 py-1.5 rounded-xl transition flex-1 text-left">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                <span>Add a card</span>
                            </button>
                            <button type="button" onclick="quickAddCard('{{ addslashes($categoryName) }}')" 
                                    class="text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-white/60 dark:hover:bg-slate-700/50 p-1.5 rounded-xl transition" title="Tambah kartu">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- "+ Add another list" Column Button -->
                <div id="add-list-container" class="w-72 sm:w-80 flex-shrink-0">
                    <button type="button" id="btn-show-add-list"
                            class="w-full flex items-center gap-2.5 rounded-2xl border border-slate-300/80 dark:border-slate-700/80 bg-slate-200/70 hover:bg-slate-200 dark:bg-[#181f2c]/70 dark:hover:bg-[#181f2c] p-3.5 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm transition">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add another list</span>
                    </button>

                    <!-- Add List Form (Inline Hidden) -->
                    <div id="form-add-list" class="hidden rounded-2xl p-3 shadow-md border border-slate-300 dark:border-slate-700 bg-slate-200 dark:bg-[#181f2c]">
                        <input type="text" id="new-list-title" placeholder="Enter list title..." maxlength="60"
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-[#111827] px-3.5 py-2 text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-0">
                        <div class="mt-2.5 flex items-center gap-2">
                            <button type="button" id="btn-submit-add-list"
                                    class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-4 py-2 text-xs font-bold text-white transition">
                                Add list
                            </button>
                            <button type="button" id="btn-cancel-add-list"
                                    class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- Archived Tasks Bottom Drawer (Collapsible) -->
        @if($archivedTodos->isNotEmpty())
            <footer class="border-t border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-[#131b2a]/90 backdrop-blur-md px-4 py-2 sm:px-6">
                <details class="group">
                    <summary class="flex items-center justify-between cursor-pointer list-none py-1 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-white transition">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            <span>Daftar Arsip Tugas ({{ $archivedTodos->count() }})</span>
                        </span>
                        <span class="text-[10px] text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <div class="pt-3 pb-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-48 overflow-y-auto">
                        @foreach($archivedTodos as $archived)
                            <div class="rounded-xl bg-white dark:bg-[#1a2230] border border-slate-200 dark:border-slate-700/60 p-3 flex items-center justify-between shadow-sm">
                                <div class="truncate mr-2">
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $archived->title }}</p>
                                    <p class="text-[10px] text-slate-400">Arsip: {{ $archived->archived_at->format('d M Y') }}</p>
                                </div>
                                <form method="POST" action="{{ route('todos.restore', $archived) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white dark:bg-indigo-900/40 dark:text-indigo-300 px-2.5 py-1 text-[11px] font-bold transition">
                                        Pulihkan
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </details>
            </footer>
        @endif
    </div>

    {{-- ==================== MODALS ==================== --}}

    <!-- Add Task Modal -->
    <div id="add-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white dark:bg-[#181f2c] border border-slate-200 dark:border-slate-700/80 p-5 shadow-2xl sm:p-7 text-slate-800 dark:text-slate-100">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Task Board</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">Tambah Kartu Baru ✨</h3>
                </div>
                <button type="button" id="close-add-modal" class="rounded-xl px-3 py-2 text-2xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700/50 transition" aria-label="Tutup">×</button>
            </div>
            <form id="add-todo" method="POST" action="{{ route('todos.store') }}" class="flex flex-col gap-4">
                @csrf
                <div class="relative">
                    <label for="todo-title" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Judul Kartu *</label>
                    <input id="todo-title" type="text" name="title" required autocomplete="off" maxlength="255"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-3 text-sm font-medium text-slate-800 dark:text-white placeholder-slate-400 transition focus:border-indigo-500 focus:bg-white dark:focus:bg-[#111827] focus:ring-0" 
                           placeholder="Contoh: Life Update Moon October...">
                    <span id="title-count" class="absolute bottom-2.5 right-4 text-[10px] text-slate-400">0/255</span>
                </div>

                <div class="relative">
                    <label for="todo-description" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Deskripsi / Catatan Tambahan</label>
                    <textarea id="todo-description" name="description" rows="3" maxlength="1000"
                              class="w-full resize-y rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-3 text-sm font-medium text-slate-800 dark:text-white placeholder-slate-400 transition focus:border-indigo-500 focus:bg-white dark:focus:bg-[#111827] focus:ring-0"
                              placeholder="Tulis deskripsi atau catatan kartu di sini..."></textarea>
                    <span id="description-count" class="absolute bottom-2.5 right-4 text-[10px] text-slate-400">0/1000</span>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">List / Kategori *</label>
                        <input type="text" name="category" id="add-modal-category" list="category-options" value="{{ $categories[0] ?? '' }}" required placeholder="Nama list / kategori..."
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0">
                        <datalist id="category-options">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                            <option value="Work">
                            <option value="Personal">
                            <option value="Urgent">
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Prioritas</label>
                        <select name="priority" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0">
                            <option value="High">High Priority</option>
                            <option value="Medium" selected>Medium Priority</option>
                            <option value="Low">Low Priority</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Tanggal Tenggat</label>
                    <input type="date" name="due_date"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0">
                </div>

                <div class="mt-3 flex justify-end gap-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                    <button type="button" onclick="closeAddModal()" class="rounded-xl border border-slate-200 dark:border-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Batal
                    </button>
                    <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-6 py-2.5 text-sm font-bold text-white shadow-md transition">
                        Simpan Kartu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto backdrop-blur-sm bg-black/50 p-4">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-[#181f2c] border border-slate-200 dark:border-slate-700/80 rounded-3xl text-left overflow-hidden shadow-2xl max-w-xl w-full text-slate-800 dark:text-white">
                <form id="edit-todo-form" method="POST">
                    @csrf @method('PATCH')
                    <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between text-white">
                        <h3 class="text-lg font-bold">Edit Kartu Tugas ✨</h3>
                        <button type="button" onclick="closeEditModal()" class="text-white/80 hover:text-white text-2xl font-bold">×</button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Judul Kartu</label>
                            <input type="text" id="edit-todo-title" name="title" required 
                                   class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Catatan / Deskripsi</label>
                            <textarea id="edit-todo-description" name="description" rows="3"
                                      class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0"
                                      placeholder="Tambahkan catatan atau deskripsi..."></textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">List / Kategori</label>
                                <input type="text" id="edit-todo-category" name="category" list="category-options"
                                       class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Prioritas</label>
                                <select id="edit-todo-priority" name="priority" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0">
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Tenggat</label>
                            <input type="date" id="edit-todo-due-date" name="due_date"
                                   class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-4 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0">
                        </div>
                    </div>
                    <div class="px-6 py-4 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-[#131b28]">
                        <button type="button" onclick="closeEditModal()" class="rounded-xl border border-slate-200 dark:border-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-5 py-2.5 text-sm font-bold text-white transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rename List Modal -->
    <div id="rename-list-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-sm rounded-2xl bg-white dark:bg-[#181f2c] border border-slate-200 dark:border-slate-700 p-6 shadow-2xl text-slate-800 dark:text-white">
            <h3 class="text-base font-bold mb-3">Edit Nama List</h3>
            <input type="hidden" id="rename-list-old-name">
            <input type="text" id="rename-list-new-name" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-3.5 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0" placeholder="Nama list baru...">
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeRenameListModal()" class="rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300">Batal</button>
                <button type="button" onclick="submitRenameList()" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-4 py-2 text-xs font-bold text-white">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Quick Rename Card Modal -->
    <div id="rename-card-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-sm rounded-2xl bg-white dark:bg-[#181f2c] border border-slate-200 dark:border-slate-700 p-6 shadow-2xl text-slate-800 dark:text-white">
            <h3 class="text-base font-bold mb-3">Edit Nama Kartu</h3>
            <input type="hidden" id="rename-card-id">
            <input type="text" id="rename-card-title" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#111827] px-3.5 py-2.5 text-sm text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-0" placeholder="Judul kartu...">
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('rename-card-modal').classList.add('hidden')" class="rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300">Batal</button>
                <button type="button" onclick="submitRenameCard()" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-4 py-2 text-xs font-bold text-white">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Hidden Global Forms for Card Operations -->
    <form id="global-delete-card-form" method="POST" class="hidden">@csrf @method('DELETE')</form>
    <form id="global-toggle-complete-form" method="POST" class="hidden">@csrf @method('PATCH')</form>
    <form id="global-archive-card-form" method="POST" class="hidden">@csrf @method('PATCH')</form>

    <!-- Card Options Modal (Popup Card when clicking titik 3 on card) -->
    <div id="card-options-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm transition-opacity duration-200">
        <div class="w-full max-w-sm rounded-3xl bg-white dark:bg-[#181f2c] border border-slate-200 dark:border-slate-700/80 p-6 shadow-2xl text-slate-800 dark:text-white transform transition-all">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700 mb-4">
                <div class="truncate mr-2">
                    <span id="card-options-category" class="rounded bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider"></span>
                    <h3 id="card-options-title" class="text-sm font-bold truncate mt-1 text-slate-900 dark:text-white"></h3>
                </div>
                <button type="button" onclick="closeCardOptionsModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-xl p-1">×</button>
            </div>

            <div class="space-y-1.5 text-xs font-semibold">
                <button type="button" onclick="triggerEditFromOptionsModal()"
                        class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/60 transition">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3.536 3.536-6 6H9v-3.536z" /></svg>
                    <span>Edit Lengkap Kartu</span>
                </button>

                <button type="button" onclick="triggerRenameFromOptionsModal()"
                        class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/60 transition">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Ganti Judul Kartu</span>
                </button>

                <button type="button" onclick="triggerArchiveFromOptionsModal()"
                        class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/60 transition">
                    <svg class="w-4 h-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span>Arsipkan Kartu</span>
                </button>

                <div class="my-1 border-t border-slate-100 dark:border-slate-700"></div>

                <button type="button" onclick="triggerDeleteFromOptionsModal()"
                        class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/40 transition">
                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    <span>Hapus Kartu (Pindahkan ke Sampah)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Delete Card Confirmation Dialog (Popup Card) -->
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm transition-opacity duration-200">
        <div class="w-full max-w-sm rounded-3xl bg-white dark:bg-[#181f2c] border border-slate-200 dark:border-slate-700/80 p-6 shadow-2xl text-center transform transition-all scale-100">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 shadow-inner">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <input type="hidden" id="delete-card-target-id">
            <h3 class="text-lg font-black text-slate-900 dark:text-white">Pindahkan ke Sampah?</h3>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Kartu <span id="delete-card-target-title" class="font-bold text-slate-800 dark:text-slate-200"></span> akan dipindahkan ke folder sampah.
            </p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" onclick="closeDeleteCardModal()" class="flex-1 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    Batalkan
                </button>
                <button type="button" onclick="executeDeleteCard()" class="flex-1 rounded-xl bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-rose-600/30 transition">
                    Ya, Hapus Kartu
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Delete List Confirmation Dialog (Popup Card) -->
    <div id="delete-list-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm transition-opacity duration-200">
        <div class="w-full max-w-sm rounded-3xl bg-white dark:bg-[#181f2c] border border-slate-200 dark:border-slate-700/80 p-6 shadow-2xl text-center transform transition-all scale-100">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 shadow-inner">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <input type="hidden" id="delete-list-target-input">
            <h3 class="text-lg font-black text-slate-900 dark:text-white">Hapus List Ini?</h3>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                List <span id="delete-list-target-text" class="font-bold text-slate-800 dark:text-slate-200"></span> dan seluruh kartu di dalamnya akan dihapus.
            </p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" onclick="closeDeleteListModal()" class="flex-1 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    Batalkan
                </button>
                <button type="button" onclick="executeDeleteList()" class="flex-1 rounded-xl bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-rose-600/30 transition">
                    Ya, Hapus List
                </button>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts Modal -->
    <div id="shortcuts-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl bg-white dark:bg-[#181f2c] border border-slate-200 dark:border-slate-700 p-6 shadow-2xl text-slate-800 dark:text-white">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700 mb-4">
                <h3 class="text-base font-bold flex items-center gap-2">
                    <span>⌨️ Pintasan Keyboard</span>
                </h3>
                <button type="button" onclick="document.getElementById('shortcuts-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-xl">×</button>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-slate-600 dark:text-slate-300">Buat tugas baru</span>
                    <kbd class="rounded bg-slate-100 dark:bg-slate-700 px-2 py-1 font-mono font-bold">N</kbd>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-slate-600 dark:text-slate-300">Cari tugas</span>
                    <kbd class="rounded bg-slate-100 dark:bg-slate-700 px-2 py-1 font-mono font-bold">/</kbd>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-slate-600 dark:text-slate-300">Edit kartu terpilih</span>
                    <kbd class="rounded bg-slate-100 dark:bg-slate-700 px-2 py-1 font-mono font-bold">E</kbd>
                </div>
                <div class="flex items-center justify-between py-1.5">
                    <span class="text-slate-600 dark:text-slate-300">Tutup modal dialog</span>
                    <kbd class="rounded bg-slate-100 dark:bg-slate-700 px-2 py-1 font-mono font-bold">Esc</kbd>
                </div>
            </div>
            <div class="mt-5 text-right">
                <button type="button" onclick="document.getElementById('shortcuts-modal').classList.add('hidden')" class="rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-4 py-2 text-xs font-semibold transition">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Scripts: Drag & Drop, Dropdowns, Add List, and Card Operations -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const STORAGE_KEY = 'trello_user_lists_clean_{{ Auth::id() }}';
        const LIST_ORDER_KEY = 'trello_user_list_order_{{ Auth::id() }}';

        if (localStorage.getItem('trello_user_custom_lists')) {
            localStorage.removeItem('trello_user_custom_lists');
        }

        function getSavedLists() {
            try {
                return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            } catch (e) {
                return [];
            }
        }

        function saveLists(lists) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(lists));
        }

        function getListOrder() {
            try {
                return JSON.parse(localStorage.getItem(LIST_ORDER_KEY) || '[]');
            } catch (e) {
                return [];
            }
        }

        function saveListOrder() {
            localStorage.setItem(LIST_ORDER_KEY, JSON.stringify(
                Array.from(document.querySelectorAll('#board-columns-container .trello-column'))
                    .map(column => column.dataset.category)
            ));
        }

        function applyListOrder() {
            const container = document.getElementById('board-columns-container');
            const addListContainer = document.getElementById('add-list-container');
            const columns = Array.from(container.querySelectorAll('.trello-column'));
            const savedOrder = getListOrder();
            const ordered = [
                ...savedOrder.map(category => columns.find(column => column.dataset.category === category)).filter(Boolean),
                ...columns.filter(column => !savedOrder.includes(column.dataset.category))
            ];

            ordered.forEach(column => container.insertBefore(column, addListContainer));
        }

        let currentActiveCard = null;

        // Card Options Modal (titik 3 on card)
        function openCardOptionsModal(btn) {
            const card = btn.closest('.todo-card');
            if (!card) return;
            currentActiveCard = card;

            document.getElementById('card-options-title').textContent = card.dataset.title;
            document.getElementById('card-options-category').textContent = card.dataset.category;

            const modal = document.getElementById('card-options-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCardOptionsModal() {
            const modal = document.getElementById('card-options-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Direct Card Edit (Pencil button or from modal)
        function handleCardEditClick(btn) {
            const card = btn.closest('.todo-card');
            if (!card) return;
            openEditModal(
                card.dataset.id,
                card.dataset.title,
                card.dataset.description,
                card.dataset.category,
                card.dataset.dueDate,
                card.dataset.priority,
                card.dataset.reminderAt
            );
        }

        function triggerEditFromOptionsModal() {
            closeCardOptionsModal();
            if (currentActiveCard) {
                openEditModal(
                    currentActiveCard.dataset.id,
                    currentActiveCard.dataset.title,
                    currentActiveCard.dataset.description,
                    currentActiveCard.dataset.category,
                    currentActiveCard.dataset.dueDate,
                    currentActiveCard.dataset.priority,
                    currentActiveCard.dataset.reminderAt
                );
            }
        }

        // Direct Card Delete (Trash button or from modal)
        function handleCardDeleteClick(btn) {
            const card = btn.closest('.todo-card');
            if (!card) return;
            openDeleteCardModal(card.dataset.id, card.dataset.title);
        }

        function triggerDeleteFromOptionsModal() {
            closeCardOptionsModal();
            if (currentActiveCard) {
                openDeleteCardModal(currentActiveCard.dataset.id, currentActiveCard.dataset.title);
            }
        }

        function openDeleteCardModal(cardId, cardTitle) {
            document.getElementById('delete-card-target-id').value = cardId;
            document.getElementById('delete-card-target-title').textContent = `"${cardTitle}"`;
            const modal = document.getElementById('delete-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteCardModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function executeDeleteCard() {
            const cardId = document.getElementById('delete-card-target-id').value;
            if (!cardId) return;

            const form = document.getElementById('global-delete-card-form');
            form.action = `/todos/${cardId}`;
            form.submit();
        }

        function triggerRenameFromOptionsModal() {
            closeCardOptionsModal();
            if (currentActiveCard) {
                quickRenameCard(currentActiveCard.dataset.id, currentActiveCard.dataset.title);
            }
        }

        function triggerToggleCompleteFromOptionsModal() {
            closeCardOptionsModal();
            if (!currentActiveCard) return;
            const cardId = currentActiveCard.dataset.id;
            const form = document.getElementById('global-toggle-complete-form');
            form.action = `/todos/${cardId}`;
            form.submit();
        }

        function triggerArchiveFromOptionsModal() {
            closeCardOptionsModal();
            if (!currentActiveCard) return;
            const cardId = currentActiveCard.dataset.id;
            const form = document.getElementById('global-archive-card-form');
            form.action = `/todos/${cardId}/archive`;
            form.submit();
        }

        // Toggle card dropdown
        function toggleCardDropdown(event, dropdownId) {
            event.stopPropagation();
            document.querySelectorAll('.card-dropdown, .column-menu-dropdown').forEach(d => {
                if (d.id !== dropdownId) d.classList.add('hidden');
            });
            const menu = document.getElementById(dropdownId);
            menu?.classList.toggle('hidden');
        }

        // Toggle column menu
        function toggleColumnMenu(event, btn) {
            event.stopPropagation();
            document.querySelectorAll('.card-dropdown, .column-menu-dropdown').forEach(d => d.classList.add('hidden'));
            const menu = btn.parentElement.querySelector('.column-menu-dropdown');
            menu?.classList.toggle('hidden');
        }

        // Close dropdowns on outside click
        window.addEventListener('click', () => {
            document.querySelectorAll('.card-dropdown, .column-menu-dropdown').forEach(d => d.classList.add('hidden'));
        });

        // Quick Rename Card
        function quickRenameCard(id, currentTitle) {
            document.getElementById('rename-card-id').value = id;
            document.getElementById('rename-card-title').value = currentTitle;
            document.getElementById('rename-card-modal').classList.remove('hidden');
            document.getElementById('rename-card-modal').classList.add('flex');
            setTimeout(() => document.getElementById('rename-card-title').focus(), 50);
        }

        function submitRenameCard() {
            const id = document.getElementById('rename-card-id').value;
            const newTitle = document.getElementById('rename-card-title').value.trim();
            if (!newTitle) return;

            fetch(`/todos/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-HTTP-Method-Override': 'PATCH',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ title: newTitle })
            }).then(() => {
                const card = document.querySelector(`.todo-card[data-id="${id}"]`);
                if (card) {
                    const titleEl = card.querySelector('.card-title');
                    if (titleEl) titleEl.textContent = newTitle;
                }
                document.getElementById('rename-card-modal').classList.add('hidden');
            }).catch(err => {
                console.error(err);
                window.location.reload();
            });
        }

        // Column Rename
        function openRenameListModal(categoryName) {
            document.getElementById('rename-list-old-name').value = categoryName;
            document.getElementById('rename-list-new-name').value = categoryName;
            document.getElementById('rename-list-modal').classList.remove('hidden');
            document.getElementById('rename-list-modal').classList.add('flex');
            setTimeout(() => document.getElementById('rename-list-new-name').focus(), 50);
        }

        function closeRenameListModal() {
            document.getElementById('rename-list-modal').classList.add('hidden');
            document.getElementById('rename-list-modal').classList.remove('flex');
        }

        function submitRenameList() {
            const oldName = document.getElementById('rename-list-old-name').value;
            const newName = document.getElementById('rename-list-new-name').value.trim();
            if (!newName || newName === oldName) {
                closeRenameListModal();
                return;
            }

            // Update in localStorage
            let saved = getSavedLists();
            const idx = saved.indexOf(oldName);
            if (idx !== -1) {
                saved[idx] = newName;
                saveLists(saved);
            }

            // Send rename request to backend
            fetch("{{ route('todos.categories.rename') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-HTTP-Method-Override': 'PATCH',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ old_category: oldName, new_category: newName })
            }).then(() => {
                window.location.reload();
            }).catch(err => {
                console.error(err);
                window.location.reload();
            });
        }

        // Column Delete via Modal Popup (No Windows Alert)
        function confirmDeleteList(categoryName) {
            document.getElementById('delete-list-target-input').value = categoryName;
            document.getElementById('delete-list-target-text').textContent = `"${categoryName}"`;
            const modal = document.getElementById('delete-list-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteListModal() {
            const modal = document.getElementById('delete-list-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function executeDeleteList() {
            const categoryName = document.getElementById('delete-list-target-input').value;
            if (!categoryName) return;

            // Remove from localStorage
            let saved = getSavedLists();
            saveLists(saved.filter(c => c !== categoryName));

            // Send delete request to backend
            fetch("{{ route('todos.categories.delete') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-HTTP-Method-Override': 'DELETE',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ category: categoryName })
            }).then(() => {
                const col = document.querySelector(`.trello-column[data-category="${categoryName}"]`);
                if (col) {
                    col.style.opacity = '0';
                    col.style.transform = 'scale(0.95)';
                    col.style.transition = 'all 0.2s ease';
                    setTimeout(() => col.remove(), 200);
                }
                closeDeleteListModal();
                window.showToast(`List "${categoryName}" berhasil dihapus.`, 'success');
            }).catch(err => {
                console.error(err);
                closeDeleteListModal();
                window.showToast('Gagal menghapus list. Silakan coba lagi.', 'error');
            });
        }

        // Full Edit Modal
        function openEditModal(id, title, description, category, dueDate, priority, reminderAt) {
            const modal = document.getElementById('edit-modal');
            const form = document.getElementById('edit-todo-form');
            form.action = `/todos/${id}`;
            document.getElementById('edit-todo-title').value = title;
            document.getElementById('edit-todo-description').value = description || '';
            document.getElementById('edit-todo-category').value = category || 'Personal';
            document.getElementById('edit-todo-due-date').value = dueDate || '';
            document.getElementById('edit-todo-priority').value = priority || 'Medium';
            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        // Add Card Modal
        function openAddModal(prefilledCategory = '') {
            const addModal = document.getElementById('add-modal');
            if (prefilledCategory) {
                document.getElementById('add-modal-category').value = prefilledCategory;
            }
            addModal.classList.remove('hidden');
            addModal.classList.add('flex');
            setTimeout(() => document.getElementById('todo-title').focus(), 50);
        }

        function closeAddModal() {
            const addModal = document.getElementById('add-modal');
            addModal.classList.add('hidden');
            addModal.classList.remove('flex');
        }

        function quickAddCard(category = '') {
            openAddModal(category);
        }

        // Create New Empty List Column Card in DOM without creating todo
        function createNewEmptyColumnDOM(listTitle) {
            const container = document.getElementById('board-columns-container');
            const addListContainer = document.getElementById('add-list-container');
            
            // Check if column already exists
            if (document.querySelector(`.trello-column[data-category="${listTitle}"]`)) {
                return;
            }

            const col = document.createElement('div');
            col.className = 'trello-column w-72 sm:w-80 flex-shrink-0 rounded-2xl p-3 shadow-md border border-slate-200 dark:border-slate-800/80 bg-slate-200/80 dark:bg-[#181f2c] flex flex-col max-h-[calc(100vh-170px)]';
            col.setAttribute('data-category', listTitle);
            col.setAttribute('data-custom-list', 'true');

            const escapedTitle = listTitle.replace(/"/g, '&quot;');
            col.innerHTML = `
                <div class="column-drag-handle flex items-center justify-between px-2 py-1.5 mb-2 cursor-grab active:cursor-grabbing">
                    <div class="flex items-center gap-2 truncate">
                        <h2 class="column-name text-sm font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-100 truncate">
                            ${escapedTitle}
                        </h2>
                    </div>
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-white/70 dark:bg-slate-800 px-2 py-0.5 rounded-md count-badge border border-slate-200/60 dark:border-slate-700">0</span>
                        <div class="relative inline-block text-left">
                            <button type="button" onclick="toggleColumnMenu(event, this)"
                                    class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-300/60 dark:hover:bg-slate-700/60 rounded-lg p-1 transition" title="Menu list">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/></svg>
                            </button>
                            <div class="column-menu-dropdown hidden absolute right-0 z-40 mt-1 w-44 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl dark:border-slate-700 dark:bg-slate-800 text-xs">
                                <button type="button" onclick="openRenameListModal('${escapedTitle}')"
                                        class="flex w-full items-center gap-2 rounded-lg px-2.5 py-1.5 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/70 transition">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Edit Nama List</span>
                                </button>
                                <button type="button" onclick="quickAddCard('${escapedTitle}')"
                                        class="flex w-full items-center gap-2 rounded-lg px-2.5 py-1.5 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700/70 transition">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tambah Kartu</span>
                                </button>
                                <div class="my-1 border-t border-slate-100 dark:border-slate-700"></div>
                                <button type="button" onclick="confirmDeleteList('${escapedTitle}')"
                                        class="flex w-full items-center gap-2 rounded-lg px-2.5 py-1.5 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/40 transition">
                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus List</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sortable-list list-scrollbar flex-1 overflow-y-auto space-y-2.5 px-0.5 py-1 min-h-[60px]"
                     data-column-type="category" data-column-val="${escapedTitle}">
                    <div class="empty-placeholder text-center py-8 text-xs text-slate-400 dark:text-slate-500 border border-dashed border-slate-300 dark:border-slate-700/60 rounded-xl">
                        Belum ada kartu di list ini.
                    </div>
                </div>
                <div class="pt-2 mt-2 border-t border-slate-300/60 dark:border-slate-700/60 flex items-center justify-between">
                    <button type="button" onclick="quickAddCard('${escapedTitle}')"
                            class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-white/60 dark:hover:bg-slate-700/50 px-3 py-1.5 rounded-xl transition flex-1 text-left">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add a card</span>
                    </button>
                    <button type="button" onclick="quickAddCard('${escapedTitle}')" 
                            class="text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-white/60 dark:hover:bg-slate-700/50 p-1.5 rounded-xl transition" title="Tambah kartu">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </button>
                </div>
            `;

            container.insertBefore(col, addListContainer);
            initSortable(col.querySelector('.sortable-list'));
        }

        function initSortable(listEl) {
            if (!listEl || typeof Sortable === 'undefined') return;
            new Sortable(listEl, {
                group: 'kanban-cards',
                animation: 200,
                ghostClass: 'opacity-30',
                chosenClass: 'scale-[1.02]',
                dragClass: 'shadow-2xl',
                filter: 'button, input, textarea, a, form, select, .no-drag, [data-no-drag]',
                preventOnFilter: false,
                onAdd: function (evt) {
                    const cardId = evt.item.getAttribute('data-id');
                    const targetCol = evt.to;
                    const colVal = targetCol.getAttribute('data-column-val');

                    fetch(`/todos/${cardId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'X-HTTP-Method-Override': 'PATCH',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ category: colVal })
                    }).then(() => {
                        saveCardOrder(targetCol);
                        updateListCounts();
                    }).catch(err => {
                        console.error('Drag error:', err);
                        window.location.reload();
                    });
                },
                onUpdate: function (evt) {
                    saveCardOrder(evt.to);
                },
                onRemove: function (evt) {
                    saveCardOrder(evt.from);
                }
            });
        }

        function saveCardOrder(listEl) {
            const items = Array.from(listEl.querySelectorAll('.todo-card')).map((card, position) => ({
                id: Number(card.dataset.id),
                position
            }));

            fetch('{{ route('todos.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ items })
            }).catch(() => window.location.reload());
        }

        function updateListCounts() {
            document.querySelectorAll('.trello-column').forEach((col) => {
                const badge = col.querySelector('.count-badge');
                const cards = col.querySelectorAll('.todo-card');
                if (badge) badge.textContent = cards.length;
                const placeholder = col.querySelector('.empty-placeholder');
                if (placeholder) {
                    placeholder.classList.toggle('hidden', cards.length > 0);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Restore any additional empty lists saved in localStorage
            const savedLists = getSavedLists();
            savedLists.forEach(title => {
                createNewEmptyColumnDOM(title);
            });

            // Initialize Sortable on all existing columns
            document.querySelectorAll('.sortable-list').forEach(initSortable);
            applyListOrder();
            new Sortable(document.getElementById('board-columns-container'), {
                animation: 200,
                draggable: '.trello-column',
                handle: '.column-drag-handle',
                filter: '#add-list-container',
                onEnd: function () {
                    requestAnimationFrame(saveListOrder);
                }
            });

            // Counter listeners
            const updateCounter = (input, counter) => {
                const el = document.getElementById(counter);
                if (el) el.textContent = `${input.value.length}/${input.maxLength}`;
            };
            document.getElementById('todo-title')?.addEventListener('input', (e) => updateCounter(e.target, 'title-count'));
            document.getElementById('todo-description')?.addEventListener('input', (e) => updateCounter(e.target, 'description-count'));

            // Add modal listeners
            document.getElementById('open-add-modal')?.addEventListener('click', () => openAddModal());
            document.getElementById('close-add-modal')?.addEventListener('click', closeAddModal);
            document.getElementById('add-modal')?.addEventListener('click', (e) => {
                if (e.target === document.getElementById('add-modal')) closeAddModal();
            });

            // Edit button bindings
            document.querySelectorAll('.edit-button').forEach((button) => {
                button.addEventListener('click', () => openEditModal(
                    button.dataset.id,
                    button.dataset.title,
                    button.dataset.description,
                    button.dataset.category,
                    button.dataset.dueDate,
                    button.dataset.priority,
                    button.dataset.reminderAt,
                ));
            });

            // Delete modal bindings
            const deleteModal = document.getElementById('delete-modal');
            let deleteForm;
            document.querySelectorAll('.delete-todo-form').forEach((form) => form.addEventListener('submit', (e) => {
                e.preventDefault();
                deleteForm = form;
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
            }));
            document.getElementById('cancel-delete')?.addEventListener('click', () => {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
            });
            document.getElementById('confirm-delete')?.addEventListener('click', () => deleteForm?.submit());

            // Inline "+ Add another list" (Creates empty card column directly without todo)
            const btnShowAddList = document.getElementById('btn-show-add-list');
            const formAddList = document.getElementById('form-add-list');
            const btnCancelAddList = document.getElementById('btn-cancel-add-list');
            const btnSubmitAddList = document.getElementById('btn-submit-add-list');
            const inputNewList = document.getElementById('new-list-title');

            btnShowAddList?.addEventListener('click', () => {
                btnShowAddList.classList.add('hidden');
                formAddList.classList.remove('hidden');
                inputNewList.focus();
            });

            btnCancelAddList?.addEventListener('click', () => {
                formAddList.classList.add('hidden');
                btnShowAddList.classList.remove('hidden');
                inputNewList.value = '';
            });

            btnSubmitAddList?.addEventListener('click', () => {
                const title = inputNewList.value.trim();
                if (!title) return;
                
                // 1. Immediately create column card in DOM
                createNewEmptyColumnDOM(title);

                // 2. Save in localStorage so it persists on reload
                let saved = getSavedLists();
                if (!saved.includes(title)) {
                    saved.push(title);
                    saveLists(saved);
                }
                saveListOrder();

                // Reset form
                formAddList.classList.add('hidden');
                btnShowAddList.classList.remove('hidden');
                inputNewList.value = '';

                // Scroll horizontally to the new list
                const container = document.querySelector('main');
                if (container) container.scrollTo({ left: container.scrollWidth, behavior: 'smooth' });
            });

            inputNewList?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    btnSubmitAddList.click();
                } else if (e.key === 'Escape') {
                    btnCancelAddList.click();
                }
            });

            // Keyboard Shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.altKey || e.ctrlKey || e.metaKey) return;
                const active = document.activeElement;
                if (active && (active.isContentEditable || active.closest('input, textarea, select, [contenteditable="true"]'))) return;

                if (e.key === 'Escape') {
                    closeCardOptionsModal();
                    closeDeleteCardModal();
                    closeDeleteListModal();
                    closeAddModal();
                    closeEditModal();
                    closeRenameListModal();
                    document.getElementById('rename-card-modal')?.classList.add('hidden');
                    document.getElementById('shortcuts-modal')?.classList.add('hidden');
                    return;
                }

                if (e.key.toLowerCase() === 'n') {
                    e.preventDefault();
                    openAddModal();
                    return;
                }

                if (e.key === '/') {
                    e.preventDefault();
                    document.getElementById('filter-drawer')?.classList.remove('hidden');
                    document.querySelector('input[type="search"][name="search"]')?.focus();
                    return;
                }

                const focusedCard = document.activeElement?.closest('.todo-card');
                if (!focusedCard) return;

                if (e.key.toLowerCase() === 'e') {
                    e.preventDefault();
                    focusedCard.querySelector('.edit-button')?.click();
                } else if (e.key.toLowerCase() === 'd') {
                    e.preventDefault();
                    focusedCard.querySelector('form[action*="todos/"][action$="update"] button')?.click();
                }
            });
        });
    </script>
</x-app-layout>
