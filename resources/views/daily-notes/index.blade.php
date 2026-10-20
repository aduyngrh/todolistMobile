<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-amber-600">Ruang Daily</p>
                <h2 class="mt-1 text-2xl font-black text-gray-900 dark:text-white">Catatan Daily</h2>
            </div>
            <button type="button" id="open-note-form"
                class="rounded-xl bg-amber-600 px-4 py-2 text-center text-sm font-bold text-white hover:bg-amber-700">+
                Catatan Daily</button>
        </div>
    </x-slot>

    <div class="min-h-screen bg-amber-50/60 py-8 dark:bg-gray-950 sm:py-12">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div id="note-modal"
                class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
                <form id="note-form" method="POST" action="{{ route('daily-notes.store') }}"
                    class="w-full max-w-2xl rounded-2xl border border-amber-200 bg-[#fffdf5] p-5 shadow-2xl dark:border-gray-800 dark:bg-gray-900">
                    @csrf
                    <input type="hidden" id="note-method" name="_method" value="POST">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 id="note-modal-title" class="text-lg font-black text-gray-900 dark:text-white">Catatan Daily
                        </h3>
                        <button type="button" id="close-note-modal"
                            class="rounded-xl px-3 py-1 text-2xl text-gray-400 hover:bg-amber-100 hover:text-gray-700 dark:hover:bg-gray-800"
                            aria-label="Tutup modal">×</button>
                    </div>
                    <label for="note-date"
                        class="mb-1 block text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-300">Tanggal
                        catatan</label>
                    <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <input id="note-date" type="date" name="note_date" value="{{ $date }}" required
                                class="w-full rounded-xl border border-amber-200 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-amber-500 focus:ring-amber-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        </div>
                    </div>
                    <input id="note-title" name="title" maxlength="255" placeholder="Judul catatan (opsional)"
                        class="mb-3 w-full border-0 border-b border-amber-200 bg-transparent px-1 py-2 text-lg font-bold text-gray-800 placeholder-amber-300 focus:border-amber-500 focus:ring-0 dark:border-gray-700 dark:bg-transparent dark:text-white">
                    <label for="note-content" class="sr-only">Isi catatan</label>
                    <textarea id="note-content" name="content" required maxlength="10000" rows="8"
                        placeholder="Tulis catatan hari ini..."
                        class="w-full resize-y border-0 bg-transparent px-1 py-2 text-sm leading-7 text-gray-700 placeholder-amber-300 focus:ring-0 dark:bg-transparent dark:text-gray-200"></textarea>
                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" id="cancel-note-modal"
                            class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Batal</button>
                        <button id="note-submit" type="submit"
                            class="rounded-xl bg-amber-600 px-4 py-2 text-sm font-bold text-white hover:bg-amber-700">Simpan
                            catatan</button>
                    </div>
                </form>
            </div>

            <div class="max-h-[70vh] overflow-y-auto pr-2">
                <div class="grid grid-cols-1 items-start gap-10 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($notes as $note)
                        <article
                            class="relative flex h-fit max-h-[28rem] w-full flex-col overflow-hidden rounded-xl border border-amber-200 bg-[#fffdf5] p-5 shadow-md dark:border-gray-800 dark:bg-gray-900">
                        <div class="absolute inset-x-0 top-0 h-1 bg-amber-400"></div>
                        <h3 class="pr-6 text-lg font-black text-gray-800 dark:text-white">
                            {{ $note->title ?: 'Catatan harian' }}</h3>
                            <p class="mt-3 max-h-56 overflow-y-auto whitespace-pre-line pr-2 text-sm leading-7 text-gray-700 dark:text-gray-300">
                            {{ $note->content }}</p>
                        <div
                            class="mt-5 flex items-center justify-between gap-3 border-t border-amber-100 pt-3 text-xs text-gray-400 dark:border-gray-800">
                            <span class="whitespace-nowrap">{{ $note->note_date->format('d M Y') }}</span>
                            <div class="flex shrink-0 items-center gap-3">
                                <button type="button" class="edit-note font-bold text-amber-600 hover:text-amber-800"
                                    data-id="{{ $note->id }}" data-date="{{ $note->note_date->format('Y-m-d') }}"
                                    data-title="{{ $note->title }}" data-content="{{ $note->content }}">Edit</button>
                                <form method="POST" action="{{ route('daily-notes.destroy', $note) }}">@csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-bold text-rose-500 hover:text-rose-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div
                        class="w-full rounded-2xl border-2 border-dashed border-amber-300 bg-white/60 p-12 text-center text-sm font-semibold text-amber-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        Belum ada catatan untuk hari ini.</div>
                @endforelse
                </div>
            </div>
        </div>
    </div>
    <script>
        const noteModal = document.getElementById('note-modal');
        const noteForm = document.getElementById('note-form');
        const closeNoteModal = () => {
            noteModal.classList.add('hidden');
            noteModal.classList.remove('flex');
        };
        document.getElementById('open-note-form').addEventListener('click', () => {
            noteModal.classList.remove('hidden');
            noteModal.classList.add('flex');
            noteForm.querySelector('input[name="title"]').focus();
        });
        document.getElementById('close-note-modal').addEventListener('click', closeNoteModal);
        document.getElementById('cancel-note-modal').addEventListener('click', closeNoteModal);
        noteModal.addEventListener('click', (event) => {
            if (event.target === noteModal) closeNoteModal();
        });
        document.querySelectorAll('.edit-note').forEach((button) => button.addEventListener('click', () => {
            noteForm.action = `/daily-notes/${button.dataset.id}`;
            document.getElementById('note-method').value = 'PATCH';
            document.getElementById('note-modal-title').textContent = 'Edit Catatan Daily';
            document.getElementById('note-submit').textContent = 'Simpan perubahan';
            document.getElementById('note-date').value = button.dataset.date;
            document.getElementById('note-title').value = button.dataset.title;
            document.getElementById('note-content').value = button.dataset.content;
            noteModal.classList.remove('hidden');
            noteModal.classList.add('flex');
            document.getElementById('note-title').focus();
        }));
    </script>
</x-app-layout>
