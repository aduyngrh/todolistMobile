<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $totalTodos = \App\Models\Todo::where('user_id', $user->id)->count();
        $completedTodos = \App\Models\Todo::where('user_id', $user->id)->where('completed', true)->count();
        $pendingTodos = \App\Models\Todo::where('user_id', $user->id)->where('completed', false)->count();
        $totalNotes = \App\Models\DailyNote::where('user_id', $user->id)->count();
        $progress = $totalTodos > 0 ? (int) round(($completedTodos / $totalTodos) * 100) : 0;

        return view('profile.edit', compact('user', 'totalTodos', 'completedTodos', 'pendingTodos', 'totalNotes', 'progress'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
