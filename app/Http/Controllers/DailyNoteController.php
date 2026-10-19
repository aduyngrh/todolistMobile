<?php

namespace App\Http\Controllers;

use App\Models\DailyNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyNoteController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        abort_unless(preg_match('/^\d{4}-\d{2}-\d{2}$/', $date), 404);

        $noteDate = \Carbon\Carbon::createFromFormat('!Y-m-d', $date)->startOfDay();
        $notes = DailyNote::where('user_id', Auth::id())
            ->orderByDesc('note_date')
            ->latest()
            ->get();

        return view('daily-notes.index', compact('date', 'noteDate', 'notes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'note_date' => 'required|date',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|max:10000',
        ]);
        $data['user_id'] = Auth::id();
        DailyNote::create($data);

        return redirect()->route('daily-notes.index')
            ->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function destroy(DailyNote $dailyNote)
    {
        abort_unless($dailyNote->user_id === Auth::id(), 403);
        $dailyNote->delete();

        return back()->with('success', 'Catatan dihapus.');
    }

    public function update(Request $request, DailyNote $dailyNote)
    {
        abort_unless($dailyNote->user_id === Auth::id(), 403);

        $dailyNote->update($request->validate([
            'note_date' => 'required|date',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|max:10000',
        ]));

        return redirect()->route('daily-notes.index')
            ->with('success', 'Catatan berhasil diperbarui.');
    }
}