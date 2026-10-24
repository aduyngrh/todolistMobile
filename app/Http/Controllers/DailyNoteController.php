<?php

namespace App\Http\Controllers;

use App\Models\DailyNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyNoteController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        abort_unless(preg_match('/^\d{4}-\d{2}-\d{2}$/', $date), 404);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'from_date' => ['nullable', 'date_format:Y-m-d'],
            'to_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from_date'],
        ]);

        $noteDate = Carbon::createFromFormat('!Y-m-d', $date)->startOfDay();
        $query = DailyNote::where('user_id', Auth::id());

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['from_date'])) {
            $query->where('note_date', '>=', $filters['from_date']);
        }

        if (! empty($filters['to_date'])) {
            $query->where('note_date', '<=', $filters['to_date']);
        }

        $notes = $query
            ->orderByDesc('note_date')
            ->latest()
            ->paginate(9)
            ->withQueryString();

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
