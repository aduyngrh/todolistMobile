<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $todos = Todo::where('user_id', Auth::id())
            ->whereNull('archived_at')
            ->get();

        $total = $todos->count();
        $completed = $todos->where('completed', true)->count();
        $pending = $total - $completed;
        $progress = $total ? round(($completed / $total) * 100) : 0;
        $overdue = $todos->filter(fn (Todo $todo): bool => ! $todo->completed && $todo->due_date?->isPast() && ! $todo->due_date->isToday())->count();
        $highPriority = $todos->where('priority', 'High')->where('completed', false)->count();
        $weekCompleted = $todos->filter(fn (Todo $todo): bool => $todo->completed && $todo->updated_at->isAfter(now()->startOfWeek()))->count();
        $topCategory = $todos->groupBy('category')->sortByDesc(fn ($items) => $items->count())->keys()->first() ?? 'Belum ada';
        $today = now()->startOfDay();
        $weekEnd = now()->copy()->addDays(7)->endOfDay();
        $todayTodos = $todos->filter(fn (Todo $todo): bool => $todo->due_date?->isToday() ?? false);
        $upcomingTodos = $todos->filter(fn (Todo $todo): bool => ! $todo->completed && $todo->due_date?->between($today, $weekEnd) ?? false)
            ->sortBy('due_date')
            ->take(5);
        $priorityTodos = $todos->where('completed', false)
            ->where('priority', 'High')
            ->sortBy('due_date')
            ->take(5);
        $weekProgress = collect(range(6, 0))->mapWithKeys(function (int $days) use ($todos): array {
            $date = now()->subDays($days);

            return [$date->format('D') => $todos->filter(fn (Todo $todo): bool => $todo->completed && $todo->updated_at->isSameDay($date))->count()];
        });

        return view('dashboard', compact('todos', 'total', 'completed', 'pending', 'progress', 'overdue', 'highPriority', 'weekCompleted', 'topCategory', 'todayTodos', 'upcomingTodos', 'priorityTodos', 'weekProgress'));
    }
}
