<?php

use App\Models\Comment;
use App\Models\Todo;
use App\Models\User;

it('adds a comment to an owned todo', function () {
    $user = User::factory()->create();
    $todo = Todo::factory()->for($user)->create();

    $response = $this->actingAs($user)->post(route('comments.store', $todo), [
        'body' => 'Komentar pertama',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('comments', [
        'todo_id' => $todo->id,
        'user_id' => $user->id,
        'body' => 'Komentar pertama',
        'parent_id' => null,
    ]);
});

it('rejects a comment on another users todo', function () {
    $user = User::factory()->create();
    $todo = Todo::factory()->create();

    $this->actingAs($user)
        ->post(route('comments.store', $todo), ['body' => 'Tidak boleh'])
        ->assertForbidden();

    expect(Comment::count())->toBe(0);
});
