<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-rose-600">Tempat sampah</p>
                <h2 class="mt-1 text-2xl font-black tracking-tight text-gray-900 dark:text-white">Todo terhapus</h2>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-8 transition-colors dark:bg-gray-950 sm:py-12">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50 p-4 text-sm text-rose-800 dark:border-rose-900 dark:bg-rose-950/30 dark:text-rose-200">
                Todo di sini masih bisa dipulihkan. Hapus permanen hanya jika sudah yakin.
            </div>

            <form method="GET" action="{{ route('todos.trash') }}" class="mb-6 flex flex-col gap-2 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900 sm:flex-row">
                <label class="flex-1">
                    <span class="sr-only">Cari todo terhapus</span>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari todo terhapus..." class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-rose-500 focus:ring-rose-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </label>
                <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-rose-700">Cari</button>
                @if(request('search'))
                    <a href="{{ route('todos.trash') }}" class="rounded-xl border border-gray-200 px-4 py-2.5 text-center text-sm font-bold text-gray-600 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Reset</a>
                @endif
            </form>

            <div class="space-y-3">
                @forelse($trashedTodos as $todo)
                    <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <h3 class="truncate font-bold text-gray-900 dark:text-white">{{ $todo->title }}</h3>
                            <p class="mt-1 text-xs text-gray-500">Dihapus {{ $todo->deleted_at->format('d M Y H:i') }}</p>
                            @if($todo->description)
                                <p class="mt-2 line-clamp-2 text-sm text-gray-500">{{ $todo->description }}</p>
                            @endif
                        </div>
                        <div class="flex shrink-0 gap-2">
                            <form method="POST" action="{{ route('todos.trash.restore', $todo) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="rounded-xl bg-emerald-100 px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-600 hover:text-white">Pulihkan</button>
                            </form>
                            <form method="POST" action="{{ route('todos.trash.force-destroy', $todo) }}" class="permanent-delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-xl bg-rose-100 px-3 py-2 text-xs font-bold text-rose-700 hover:bg-rose-600 hover:text-white">Hapus permanen</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-gray-300 bg-white p-12 text-center text-sm font-semibold text-gray-500 dark:border-gray-700 dark:bg-gray-900">Tempat sampah kosong.</div>
                @endforelse
            </div>
            @if($trashedTodos->hasPages())
                <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    {{ $trashedTodos->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="permanent-delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900">
            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:text-rose-300" aria-hidden="true"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M5 19h14L12 5 5 19Z"/></svg></div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hapus permanen?</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Todo tidak bisa dipulihkan setelah dihapus permanen.</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="cancel-permanent-delete" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Batal</button>
                <button type="button" id="confirm-permanent-delete" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Hapus permanen</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('permanent-delete-modal');
            const cancel = document.getElementById('cancel-permanent-delete');
            const confirm = document.getElementById('confirm-permanent-delete');
            let selectedForm = null;

            document.querySelectorAll('.permanent-delete-form').forEach((form) => form.addEventListener('submit', (event) => {
                event.preventDefault();
                selectedForm = form;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }));

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                selectedForm = null;
            };

            cancel.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeModal();
            });
            confirm.addEventListener('click', () => selectedForm?.submit());
        });
    </script>
</x-app-layout>
