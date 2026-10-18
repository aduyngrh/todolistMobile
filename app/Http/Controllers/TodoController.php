<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Todo::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->with('subtasks');

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('completed', $request->input('status') === 'done');
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $todos = $query
            ->orderByRaw("CASE priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        $todoItems = $todos->getCollection();

        $archivedTodos = \App\Models\Todo::where('user_id', Auth::id())
            ->whereNotNull('archived_at')
            ->orderByDesc('archived_at')
            ->get();

        return view('todos.index', compact('todos', 'todoItems', 'archivedTodos'));
    }

    public function calendar(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        abort_unless(preg_match('/^\d{4}-\d{2}$/', $month), 404);
        $monthDate = \Carbon\Carbon::createFromFormat('!Y-m', $month)->startOfMonth();
        $todos = \App\Models\Todo::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$monthDate->copy()->startOfMonth(), $monthDate->copy()->endOfMonth()])
            ->orderBy('due_date')
            ->get()
            ->groupBy(fn ($todo) => $todo->due_date->format('Y-m-d'));

        return view('todos.calendar', compact('monthDate', 'todos'));
    }

    public function planner(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        abort_unless(preg_match('/^\d{4}-\d{2}-\d{2}$/', $date), 404);

        $plannerDate = \Carbon\Carbon::createFromFormat('!Y-m-d', $date)->startOfDay();
        $todos = \App\Models\Todo::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->whereDate('due_date', $date)
            ->with('subtasks')
            ->orderByRaw('start_time IS NULL')
            ->orderBy('start_time')
            ->orderByRaw("CASE priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 ELSE 3 END")
            ->get();

        return view('todos.planner', compact('plannerDate', 'todos'));
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
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'reminder_at' => 'nullable|date',
            'priority' => 'required|string|in:High,Medium,Low',
        ]);

        \App\Models\Todo::create([
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reminder_at' => $request->reminder_at,
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
        Gate::authorize('update', $todo);
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
        if ($request->has('start_time')) {
            $todo->start_time = $request->start_time;
        }
        if ($request->has('end_time')) {
            $todo->end_time = $request->end_time;
        }
        if ($request->has('reminder_at')) {
            $todo->reminder_at = $request->reminder_at;
        }
        if ($request->has('priority')) {
            $todo->priority = $request->priority;
        }
        if (! $request->has('title') && ! $request->has('description') && ! $request->has('category') && ! $request->has('due_date') && ! $request->has('start_time') && ! $request->has('end_time') && ! $request->has('reminder_at') && ! $request->has('priority')) {
            $todo->completed = ! $todo->completed;
        }
        $todo->save();

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function archive(string $id)
    {
        $todo = \App\Models\Todo::where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('update', $todo);
        $todo->update(['archived_at' => now()]);

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil diarsipkan.');
    }

    public function restore(string $id)
    {
        $todo = \App\Models\Todo::withTrashed()->where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('restore', $todo);
        $todo->update(['archived_at' => null]);

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil dipulihkan.');
    }

    public function trash(Request $request)
    {
        $query = \App\Models\Todo::onlyTrashed()->where('user_id', Auth::id());

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $trashedTodos = $query
            ->latest('deleted_at')
            ->get();

        return view('todos.trash', compact('trashedTodos'));
    }

    public function restoreFromTrash(string $id)
    {
        $todo = \App\Models\Todo::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('restore', $todo);
        $todo->restore();

        return redirect()->route('todos.trash')->with('success', 'Tugas berhasil dipulihkan.');
    }

    public function forceDestroy(string $id)
    {
        $todo = \App\Models\Todo::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('forceDelete', $todo);
        $todo->forceDelete();

        return redirect()->route('todos.trash')->with('success', 'Tugas dihapus permanen.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = \App\Models\Todo::where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('delete', $todo);
        $todo->delete();

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil dipindahkan ke sampah.');
    }
}
