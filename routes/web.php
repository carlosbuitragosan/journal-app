<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('/dashboard', function () {
  return redirect('/journal');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::post('/journal', function (Request $request) {
  $request->validate([
      'title' => 'nullable',
      'body' => 'required',
  ]);
  JournalEntry::create([
      'user_id' => Auth::id(),
      'title' => $request->input('title'),
      'body' => $request->input('body'),
  ]);
  return redirect('/journal');
})->middleware('auth');

Route::get('/journal', function () {
  $entries = \App\Models\JournalEntry::where('user_id', Auth::id())
      ->latest()
      ->get();
  return view('journal.index', ['entries' => $entries]);
})->middleware('auth');

Route::delete('/journal/{entry}', function (\App\Models\JournalEntry $entry) {
  if ($entry->user_id !== \Illuminate\Support\Facades\Auth::id()) {
    abort(403);
  }

  $entry->delete();
  return redirect('/journal');
})->middleware('auth');

Route::patch('/journal/{entry}', function (JournalEntry $entry, Request $request) {
  abort_unless(Auth::id() === $entry->user_id, 403);

  $validated = $request->validate([
      'title' => 'nullable|string',
      'body' => 'required|string',
  ]);

  $entry->update($validated);
  return redirect('/journal');
})->middleware('auth');

require __DIR__ . '/auth.php';
