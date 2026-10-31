<section class="mt-4 rounded-2xl border border-indigo-100 bg-indigo-50/50 p-4 dark:border-indigo-900/60 dark:bg-indigo-950/20">
    <div class="mb-3 flex items-center justify-between">
        <div>
            <h4 class="text-xs font-extrabold uppercase tracking-wide text-indigo-700 dark:text-indigo-300">Komentar</h4>
            <p class="text-[11px] text-slate-500 dark:text-slate-400">Diskusi untuk tugas ini</p>
        </div>
        <span class="rounded-full bg-white px-2.5 py-1 text-[11px] font-bold text-indigo-700 shadow-sm dark:bg-slate-800 dark:text-indigo-300">{{ $todo->comments->count() }}</span>
    </div>

    <div class="space-y-3">
        @forelse($todo->comments as $comment)
            <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100">{{ $comment->user->name }}</p>
                        <p class="text-[10px] text-slate-400">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                    @can('delete', $comment)
                        <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[11px] font-semibold text-rose-600 hover:text-rose-800">Hapus</button>
                        </form>
                    @endcan
                </div>
                <p class="mt-2 whitespace-pre-line text-sm text-slate-700 dark:text-slate-200">{{ $comment->body }}</p>

                @foreach($comment->children as $reply)
                    <div class="mt-3 ml-4 rounded-xl border-l-2 border-indigo-300 bg-indigo-50/70 p-3 dark:border-indigo-700 dark:bg-indigo-950/30">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-100">{{ $reply->user->name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $reply->created_at->diffForHumans() }}</p>
                            </div>
                            @can('delete', $reply)
                                <form method="POST" action="{{ route('comments.destroy', $reply) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[11px] font-semibold text-rose-600 hover:text-rose-800">Hapus</button>
                                </form>
                            @endcan
                        </div>
                        <p class="mt-2 whitespace-pre-line text-sm text-slate-700 dark:text-slate-200">{{ $reply->body }}</p>
                    </div>
                @endforeach

                <form method="POST" action="{{ route('comments.store', $todo) }}" class="mt-3 flex gap-2">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <label class="sr-only" for="reply-{{ $comment->id }}">Balas komentar</label>
                    <input id="reply-{{ $comment->id }}" name="body" required maxlength="2000" placeholder="Balas komentar..." class="min-w-0 flex-1 rounded-xl border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-indigo-200">
                    <button type="submit" class="rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white hover:bg-indigo-700">Balas</button>
                </form>
            </div>
        @empty
            <p class="rounded-xl border border-dashed border-indigo-200 px-3 py-2 text-xs text-slate-500 dark:border-indigo-800 dark:text-slate-400">Belum ada komentar.</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('comments.store', $todo) }}" class="mt-3">
        @csrf
        <label class="sr-only" for="comment-{{ $todo->id }}">Komentar baru</label>
        <textarea id="comment-{{ $todo->id }}" name="body" required maxlength="2000" rows="2" placeholder="Tulis komentar..." class="w-full rounded-xl border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-indigo-200"></textarea>
        <button type="submit" class="mt-2 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white hover:bg-indigo-700">Tambah komentar</button>
    </form>
</section>
