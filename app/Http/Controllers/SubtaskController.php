<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SubtaskController extends Controller
{
    public function store(Request $request, Todo $todo)
    {
        abort_unless($todo->user_id === Auth::id(), 403);
        Gate::authorize('view', $todo);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);
        $todo->subtasks()->create($data);

        return back()->with('success', 'Subtask berhasil ditambahkan.');
    }

    public function update(Request $request, Subtask $subtask)
    {
        abort_unless($subtask->todo->user_id === Auth::id(), 403);
        Gate::authorize('view', $subtask->todo);

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $subtask->update($data ?: ['completed' => ! $subtask->completed]);

        return back()->with('success', 'Status subtask diperbarui.');
    }

    public function destroy(Subtask $subtask)
    {
        abort_unless($subtask->todo->user_id === Auth::id(), 403);

        $subtask->delete();

        return back()->with('success', 'Subtask dihapus.');
    }
}
