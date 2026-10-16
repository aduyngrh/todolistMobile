<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = \App\Models\Todo::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->with('subtasks')
            ->orderByRaw("CASE priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->get();

        $archivedTodos = \App\Models\Todo::where('user_id', Auth::id())
            ->whereNotNull('archived_at')
            ->orderByDesc('archived_at')
            ->get();

        return view('todos.index', compact('todos', 'archivedTodos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:Work,Personal,Urgent',
            'due_date' => 'nullable|date',
            'priority' => 'required|string|in:High,Medium,Low',
        ]);

        \App\Models\Todo::create([
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
        ]);

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = \App\Models\Todo::findOrFail($id);
        if ($request->has('title')) {
            $todo->title = $request->title;
        }
        if ($request->has('description')) {
            $todo->description = $request->description;
        }
        if ($request->has('category')) {
            $todo->category = $request->category;
        }
        if ($request->has('due_date')) {
            $todo->due_date = $request->due_date;
        }
        if ($request->has('priority')) {
            $todo->priority = $request->priority;
        }
        if (! $request->has('title') && ! $request->has('description') && ! $request->has('category') && ! $request->has('due_date') && ! $request->has('priority')) {
            $todo->completed = ! $todo->completed;
        }
        $todo->save();

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function archive(string $id)
    {
        $todo = \App\Models\Todo::where('user_id', Auth::id())->findOrFail($id);
        $todo->update(['archived_at' => now()]);

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil diarsipkan.');
    }

    public function restore(string $id)
    {
        $todo = \App\Models\Todo::withTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $todo->update(['archived_at' => null]);

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil dipulihkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = \App\Models\Todo::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil dipindahkan ke sampah.');
    }
}
