<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\DailyNoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('todos', TodoController::class);
    Route::get('/calendar', [TodoController::class, 'calendar'])->name('todos.calendar');
    Route::get('/planner', [TodoController::class, 'planner'])->name('todos.planner');
    Route::get('/daily-notes', [DailyNoteController::class, 'index'])->name('daily-notes.index');
    Route::post('/daily-notes', [DailyNoteController::class, 'store'])->name('daily-notes.store');
    Route::patch('/daily-notes/{dailyNote}', [DailyNoteController::class, 'update'])->name('daily-notes.update');
    Route::delete('/daily-notes/{dailyNote}', [DailyNoteController::class, 'destroy'])->name('daily-notes.destroy');
    Route::get('/trash', [TodoController::class, 'trash'])->name('todos.trash');
    Route::patch('/trash/{todo}/restore', [TodoController::class, 'restoreFromTrash'])->withTrashed()->name('todos.trash.restore');
    Route::delete('/trash/{todo}', [TodoController::class, 'forceDestroy'])->withTrashed()->name('todos.trash.force-destroy');
    Route::patch('/todos/{todo}/archive', [TodoController::class, 'archive'])->name('todos.archive');
    Route::patch('/todos/{todo}/restore', [TodoController::class, 'restore'])->withTrashed()->name('todos.restore');
    Route::post('/todos/{todo}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::patch('/subtasks/{subtask}', [SubtaskController::class, 'update'])->name('subtasks.update');
    Route::delete('/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');
});

require __DIR__.'/auth.php';
