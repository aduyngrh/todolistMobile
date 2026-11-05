<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $userId = auth()->id();
        $todos = Todo::where('user_id', $userId)
            ->whereNull('archived_at')
            ->get(['id', 'title', 'completed', 'priority', 'category', 'due_date', 'updated_at']);

        $total = $todos->count();
        $completed = $todos->where('completed', true)->count();
        $pending = $total - $completed;
        $progress = $total === 0 ? 0 : (int) round($completed / $total * 100);
        $overdue = $todos->filter(fn (Todo $todo): bool => ! $todo->completed && $todo->due_date?->isPast() && ! $todo->due_date->isToday())->count();
        $highPriority = $todos->where('priority', 'High')->where('completed', false)->count();
        $weekCompleted = $todos->filter(fn (Todo $todo): bool => $todo->completed && $todo->updated_at?->isAfter(now()->startOfWeek()))->count();
        $today = $todos->filter(fn (Todo $todo): bool => $todo->due_date?->isToday() && ! $todo->completed)->sortBy('priority')->take(5);

        return view('dashboard', compact('total', 'completed', 'pending', 'progress', 'overdue', 'highPriority', 'weekCompleted', 'today'));
    }
}
