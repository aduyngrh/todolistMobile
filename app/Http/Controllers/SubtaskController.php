<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    public function store(Request $request, Todo $todo)
    {
        abort_unless($todo->user_id === Auth::id(), 403);

        $data = $request->validate(['title' => 'required|string|max:255']);
        $todo->subtasks()->create($data);

        return back()->with('success', 'Subtask berhasil ditambahkan.');
    }

    public function update(Request $request, Subtask $subtask)
    {
        abort_unless($subtask->todo->user_id === Auth::id(), 403);

        $subtask->update(['completed' => ! $subtask->completed]);

        return back()->with('success', 'Status subtask diperbarui.');
    }

    public function destroy(Subtask $subtask)
    {
        abort_unless($subtask->todo->user_id === Auth::id(), 403);

        $subtask->delete();

        return back()->with('success', 'Subtask dihapus.');
    }
}
