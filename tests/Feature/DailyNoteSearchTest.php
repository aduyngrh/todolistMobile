<?php

use App\Models\DailyNote;
use App\Models\User;

it('searches note titles and content without exposing another users notes', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    DailyNote::create([
        'user_id' => $user->id,
        'note_date' => '2026-10-20',
        'title' => 'Belajar Laravel',
        'content' => 'Mengerjakan fitur baru',
    ]);
    DailyNote::create([
        'user_id' => $user->id,
        'note_date' => '2026-10-21',
        'title' => 'Catatan lain',
        'content' => 'Belajar pagination',
    ]);
    DailyNote::create([
        'user_id' => $otherUser->id,
        'note_date' => '2026-10-22',
        'title' => 'Belajar Laravel rahasia',
        'content' => 'Catatan milik orang lain',
    ]);

    $this->actingAs($user)
        ->get(route('daily-notes.index', ['search' => 'Belajar']))
        ->assertOk()
        ->assertSee('Belajar Laravel')
        ->assertSee('Belajar pagination')
        ->assertDontSee('Belajar Laravel rahasia');
});

it('filters notes by an inclusive date range', function () {
    $user = User::factory()->create();

    foreach (['2026-10-19', '2026-10-20', '2026-10-22', '2026-10-23'] as $date) {
        DailyNote::create([
            'user_id' => $user->id,
            'note_date' => $date,
            'title' => $date,
            'content' => 'Catatan '.$date,
        ]);
    }

    $this->actingAs($user)
        ->get(route('daily-notes.index', [
            'from_date' => '2026-10-20',
            'to_date' => '2026-10-22',
        ]))
        ->assertOk()
        ->assertSee('2026-10-20')
        ->assertSee('2026-10-22')
        ->assertDontSee('2026-10-19')
        ->assertDontSee('2026-10-23');
});

it('rejects a date range whose start is after its end', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('daily-notes.index'))
        ->get(route('daily-notes.index', [
            'from_date' => '2026-10-22',
            'to_date' => '2026-10-20',
        ]))
        ->assertRedirect(route('daily-notes.index'))
        ->assertSessionHasErrors('to_date');
});

it('paginates notes and keeps the active filters in pagination links', function () {
    $user = User::factory()->create();

    for ($day = 1; $day <= 10; $day++) {
        $date = sprintf('2026-10-%02d', $day);
        DailyNote::create([
            'user_id' => $user->id,
            'note_date' => $date,
            'title' => "Catatan {$day}",
            'content' => 'Catatan pagination',
        ]);
    }

    $this->actingAs($user)
        ->get(route('daily-notes.index', [
            'search' => 'pagination',
            'from_date' => '2026-10-01',
            'to_date' => '2026-10-31',
        ]))
        ->assertOk()
        ->assertSee('Catatan 10')
        ->assertDontSee('Catatan 1</h3>', false)
        ->assertSee('page=2', false)
        ->assertSee('search=pagination', false)
        ->assertSee('from_date=2026-10-01', false)
        ->assertSee('to_date=2026-10-31', false);
});
