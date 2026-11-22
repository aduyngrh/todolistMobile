<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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
        $query = Todo::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->with(['subtasks', 'comments.user', 'comments.children.user']);

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
            ->orderBy('category')
            ->orderBy('position')
            ->orderBy('id')
            ->paginate(50)
            ->withQueryString();
        $todoItems = $todos->getCollection();

        $archivedTodos = Todo::where('user_id', Auth::id())
            ->whereNotNull('archived_at')
            ->orderByDesc('archived_at')
            ->get();

        $categories = Todo::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->groupBy('category')
            ->orderByRaw('MIN(position)')
            ->orderByRaw('MIN(id)')
            ->pluck('category')
            ->toArray();

        return view('todos.index', compact('todos', 'todoItems', 'archivedTodos', 'categories'));
    }

    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer'],
            'items.*.position' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($data['items'] as $item) {
            Todo::where('id', $item['id'])
                ->where('user_id', Auth::id())
                ->update(['position' => $item['position']]);
        }

        return response()->json(['saved' => true]);
    }

    public function calendar(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        abort_unless(preg_match('/^\d{4}-\d{2}$/', $month), 404);
        $monthDate = Carbon::createFromFormat('!Y-m', $month)->startOfMonth();
        $todos = Todo::where('user_id', Auth::id())
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

        $plannerDate = Carbon::createFromFormat('!Y-m-d', $date)->startOfDay();
        $todos = Todo::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->whereDate('due_date', $date)
            ->with('subtasks')
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
            'category' => 'required|string|max:100',
            'due_date' => 'nullable|date',
            'reminder_at' => 'nullable|date',
            'priority' => 'required|string|in:High,Medium,Low',
        ]);

        $position = (int) Todo::where('user_id', Auth::id())
            ->where('category', $request->category)
            ->max('position') + 1;

        Todo::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'position' => $position,
            'due_date' => $request->due_date,
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
        $todo = Todo::findOrFail($id);
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
        if ($request->has('reminder_at')) {
            $todo->reminder_at = $request->reminder_at;
        }
        if ($request->has('priority')) {
            $todo->priority = $request->priority;
        }
        if ($request->has('completed')) {
            $todo->completed = $request->boolean('completed');
        }
        if (! $request->has('title') && ! $request->has('description') && ! $request->has('category') && ! $request->has('completed') && ! $request->has('due_date') && ! $request->has('reminder_at') && ! $request->has('priority')) {
            $todo->completed = ! $todo->completed;
        }
        $todo->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'todo' => $todo]);
        }

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function renameCategory(Request $request)
    {
        $request->validate([
            'old_category' => 'required|string',
            'new_category' => 'required|string|max:100',
        ]);

        Todo::where('user_id', Auth::id())
            ->where('category', $request->old_category)
            ->update(['category' => $request->new_category]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('todos.index')->with('success', 'Nama list berhasil diubah.');
    }

    public function deleteCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
        ]);

        Todo::where('user_id', Auth::id())
            ->where('category', $request->category)
            ->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('todos.index')->with('success', 'List berhasil dihapus.');
    }

    public function archive(string $id)
    {
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('update', $todo);
        $todo->update(['archived_at' => now()]);

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil diarsipkan.');
    }

    public function restore(string $id)
    {
        $todo = Todo::withTrashed()->where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('restore', $todo);
        $todo->update(['archived_at' => null]);

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil dipulihkan.');
    }

    public function trash(Request $request)
    {
        $query = Todo::onlyTrashed()->where('user_id', Auth::id());

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $trashedTodos = $query
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return view('todos.trash', compact('trashedTodos'));
    }

    public function restoreFromTrash(string $id)
    {
        $todo = Todo::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('restore', $todo);
        $todo->restore();

        return redirect()->route('todos.trash')->with('success', 'Tugas berhasil dipulihkan.');
    }

    public function forceDestroy(string $id)
    {
        $todo = Todo::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('forceDelete', $todo);
        $todo->forceDelete();

        return redirect()->route('todos.trash')->with('success', 'Tugas dihapus permanen.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        Gate::authorize('delete', $todo);
        $todo->delete();

        return redirect()->route('todos.index')->with('success', 'Kartu berhasil dihapus.');
    }
}
