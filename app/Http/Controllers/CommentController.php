<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(Request $request, Todo $todo): RedirectResponse
    {
        Gate::authorize('view', $todo);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        if (isset($validated['parent_id'])) {
            $parent = $todo->comments()->findOrFail($validated['parent_id']);

            if ($parent->parent_id !== null) {
                return back()->withErrors(['parent_id' => 'Balasan hanya dapat dibuat satu tingkat.']);
            }
        }

        $todo->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
