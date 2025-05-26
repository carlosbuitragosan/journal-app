<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth')->group(function () {
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
});

require __DIR__ . '/auth.php';
