<?php

use App\Models\Todo;
use App\Models\User;

test('trash search returns matching todos only', function () {
    $user = User::factory()->create();
    $matchingTodo = Todo::create(['user_id' => $user->id, 'title' => 'Bayar listrik']);
    $otherTodo = Todo::create(['user_id' => $user->id, 'title' => 'Belanja mingguan']);

    $matchingTodo->delete();
    $otherTodo->delete();

    $this->actingAs($user)
        ->get(route('todos.trash', ['search' => 'listrik']))
        ->assertOk()
        ->assertSee('Bayar listrik')
        ->assertDontSee('Belanja mingguan');
});
