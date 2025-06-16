<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\JournalEntry;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JournalController;

Route::get('/', function () {
  return Auth::check() ? redirect('/journal') : view('welcome');
});

Route::get('/dashboard', function () {
  return redirect('/journal');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
  // Profile
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  // Journal
  Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
  Route::post('journal', [JournalController::class, 'store'])->name('journal.store');
  Route::patch('/journal/{entry}', [JournalController::class, 'update'])->name('journal.update');
  Route::delete('/journal/{entry}', [JournalController::class, 'destroy'])->name('journal.destroy');
  // Calendar
  Route::get('/calendar', [JournalController::class, 'calendar'])->name('calendar');
  Route::get('/api/journal-entries', [JournalController::class, 'entriesJson'])->name('journal.entries.json');
  Route::get('/api/journal-entry/{entry}', function (App\Models\JournalEntry $entry) {
    abort_unless(Auth::id() === $entry->user_id, 403);
    return response()->json($entry);
  })->middleware('auth');
});

// Debug routes
Route::get('/test-protected', function () {
  return 'You are verified!';
})->middleware(['auth', 'verified']);

Route::get('/verify-check', function () {
  $user = Auth::user();

  return [
      'email_verified_at' => $user->email_verified_at,
      'hasVerifiedEmail()' => $user->hasVerifiedEmail(),
  ];
})->middleware(['auth', 'verified']);

Route::get('/only-verified', function () {
  return '✅ You got through verified!';
})->middleware('verified');

Route::get('/test-email', function () {
  Mail::raw('This is a test email from Reflekt via Postmark.', function ($message) {
    $message->to('c.buitragosan@gmail.com') // 👈 Replace with your real email address
            ->subject('Reflekt Email Test');
  });

  return 'Email sent! Check your inbox.';
});

require __DIR__ . '/auth.php';
