<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
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
    Route::patch('/todos/{todo}/archive', [TodoController::class, 'archive'])->name('todos.archive');
    Route::patch('/todos/{todo}/restore', [TodoController::class, 'restore'])->withTrashed()->name('todos.restore');
});

require __DIR__.'/auth.php';
