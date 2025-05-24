<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a logged-in user to create a journal entry', function () {
  $user = User::factory()->create();

  $this->actingAs($user)
      ->post('/journal', [
          'title' => 'First Entry',
          'body' => 'Today I started building my journal app!',
      ])
      ->assertRedirect('/journal');

  $this->assertDatabaseHas('journal_entries', [
      'title' => 'First Entry',
      'body' => 'Today I started building my journal app!',
  ]);
});

it('shows only the logged-in user\'s journal entries', function () {
  $user = \App\Models\User::factory()->create();
  $otherUser = \App\Models\User::factory()->create();

  //Entry that should be shown
  \App\Models\JournalEntry::create([
      'user_id' => $user->id,
      'title' => 'My private entry',
      'body' => 'This is mine!',
  ]);

  // Entry that should NOT be shown
  \App\Models\JournalEntry::create([
      'user_id' => $otherUser->id,
      'title' => 'Not mine',
      'body' => 'This belongs to someone else',
  ]);

  $this->actingAs($user)
      ->get('/journal')
      ->assertSee('My private entry')
      ->assertDontSee('Not mine');
});

it('displays the logged-in user\'s journal entries on the journal page', function () {
  $user = \App\Models\User::factory()->create();

  \App\Models\JournalEntry::create([
      'user_id' => $user->id,
      'title' => 'My first journal entry',
      'body' => 'Lorem ipsum dolor sit amet.',
  ]);

  $this->actingAs($user)
      ->get('/journal')
      ->assertSee('My first journal entry')
      ->assertSee('Lorem ipsum dolor sit amet.');
});

it('allows a user to delete their own journal entry', function () {
  $user = \App\Models\User::factory()->create();

  $entry = \App\Models\JournalEntry::create([
      'user_id' => $user->id,
      'title' => 'To be deleted',
      'body' => 'This entry will be gone',
  ]);

  $this->actingAs($user)
      ->delete("/journal/{$entry->id}")
      ->assertRedirect('/journal');

  $this->assertDatabaseMissing('journal_entries', [
      'id' => $entry->id,
  ]);
});

it('allows a user to update their journal entry', function () {
  $user = \App\Models\User::factory()->create();
  $entry = \App\Models\JournalEntry::factory()->create([
      'user_id' => $user->id,
      'title' => 'Old Title',
      'body' => 'Old Body',
  ]);

  $this->actingAs($user)
      ->patch("/journal/{$entry->id}", [
          'title' => 'Updated Title',
          'body' => 'Updated Body',
      ])
      ->assertRedirect('/journal');

  $this->assertDatabaseHas('journal_entries', [
      'id' => $entry->id,
      'title' => 'Updated Title',
      'body' => 'Updated Body',
  ]);
});
