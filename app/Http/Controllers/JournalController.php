<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\JournalEntry;

class JournalController extends Controller
{
  public function index()
  {
    $entries = JournalEntry::where('user_id', Auth::id())->latest()->get();

    return view('journal.index', ['entries' => $entries]);
  }

  public function store(Request $request)
  {
    $request->validate([
        'title' => 'nullable|string',
        'body' => 'required|string',
    ]);

    JournalEntry::create([
        'user_id' => Auth::id(),
        'title' => $request->input('title'),
        'body' => $request->input('body'),
    ]);

    return redirect()->route('journal.index');
  }

  public function update(JournalEntry $entry, Request $request)
  {
    abort_unless($entry->user_id === Auth::id(), 403);

    $validated = $request->validate([
        'title' => 'nullable|string',
        'body' => 'required|string',
    ]);

    $entry->update($validated);

    return redirect()->back();
  }

  public function destroy(JournalEntry $entry)
  {
    abort_unless($entry->user_id === Auth::id(), 403);

    $entry->delete();

    return redirect()->back();
  }

  public function calendar()
  {
    return view('calendar.index');
  }

  public function entriesJson()
  {
    $entries = JournalEntry::where('user_id', Auth::id())
        ->get()
        ->map(function ($entry) {
          return [
              'id' => $entry->id,
              'title' => $entry->title ?: Str::limit($entry->body, 30),
              'start' => $entry->created_at->toDateString(),
              'url' => '',
          ];
        });

    return response()->json($entries);
  }
}
