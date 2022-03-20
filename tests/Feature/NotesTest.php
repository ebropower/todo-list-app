<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_return_notes_for_authenticated_user()
    {
        $user = User::factory()
            ->has(Note::factory()->count(3))
            ->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v1/notes')
            ->assertSee([
                'user_id',
                $user->name
            ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_return_other_user_notes_as_authenticated_user()
    {
        $userWithNote = User::factory()
            ->has(Note::factory()->count(3))
            ->create();

        $authenticatedUser = User::factory()->create();

        Sanctum::actingAs($authenticatedUser, ['*']);

        $note = $userWithNote->notes->first();

        $response = $this->getJson('/api/v1/users/' . $userWithNote->id . '/notes/')
            ->assertSee([
                $userWithNote->name
            ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_cannot_return_notes_as_unauthenticated_user()
    {
        $response = $this->getJson('/api/v1/notes');

        $response->assertStatus(401);
    }

    /** @test */
    public function test_it_can_create_a_note_as_authenticated_user()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $payload = [
            'details' => 'Hello World',
            'completed' => 0
        ];

        $response = $this->postJson('/api/v1/notes', $payload)
            ->assertSee([
                'user_id' => $user->id,
                'details' => $payload['details'],
                $user->name
            ]);

        $this->assertDatabaseHas('notes', [
            'id' => $user->notes->first()->id,
            'user_id' => $user->id,
            'details' => $payload['details']
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function test_it_cannot_create_a_note_when_validation_fails()
    {
        $user = User::factory()
            ->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/v1/notes', [
            'details' => 5,
            'completed' => "finished"
        ])
            ->assertInvalid([
                'details',
                'completed'
            ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function test_it_can_view_a_note_as_authenticated_user()
    {
        $user = User::factory()
            ->has(Note::factory())
            ->create();

        Sanctum::actingAs($user, ['*']);

        $note = $user->notes->first();

        $response = $this->getJson('/api/v1/notes/' . $note->id)
            ->assertSee([
                'id' => $note->id,
                'user_id' => $user->id,
                'details' => $note->details,
                $user->name
            ]);

        $response->assertSuccessful();
    }

    /** @test */
    public function test_it_can_mark_note_as_complete_as_authenticated_user()
    {
        $user = User::factory()
            ->has(Note::factory()->state([
                'completed_at' => null
            ]))
            ->create();

        Sanctum::actingAs($user, ['*']);

        $note = $user->notes->first();

        $payload = [
            'details' => $note->details,
            'completed' => 1
        ];

        $response = $this->putJson('/api/v1/notes/' . $note->id, $payload);

        $this->assertNotNull($note->refresh()->completed_at);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_mark_note_as_incomplete_as_authenticated_user()
    {
        $user = User::factory()
            ->has(Note::factory()->state([
                'completed_at' => now()
            ]))
            ->create();

        Sanctum::actingAs($user, ['*']);

        $note = $user->notes->first();

        $payload = [
            'details' => $note->details,
            'completed' => 0
        ];

        $response = $this->putJson('/api/v1/notes/' . $note->id, $payload);

        $this->assertNull($note->refresh()->completed_at);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_cannot_mark_note_as_complete_when_validation_fails()
    {
        $user = User::factory()
            ->has(Note::factory()->state([
                'completed_at' => null
            ]))
            ->create();

        Sanctum::actingAs($user, ['*']);

        $note = $user->notes->first();

        $payload = [
            'details' => $note->details,
            'completed' => 3
        ];

        $response = $this->putJson('/api/v1/notes/' . $note->id, $payload)
            ->assertInvalid([
                'completed'
            ]);

        $this->assertNull($note->refresh()->completed_at);

        $response->assertStatus(422);
    }

    /** @test */
    public function test_it_cannot_mark_note_as_complete_for_other_user()
    {
        $userWithNote = User::factory()
            ->has(Note::factory()->state([
                'completed_at' => null
            ]))
            ->create();

        $authenticatedUser = User::factory()->create();

        Sanctum::actingAs($authenticatedUser, ['*']);

        $note = $userWithNote->notes->first();

        $payload = [
            'details' => $note->details,
            'completed' => 1
        ];

        $response = $this->putJson('/api/v1/notes/' . $note->id, $payload)
            ->assertSee('unauthorized');

        $this->assertNull($note->refresh()->completed_at);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_it_can_delete_own_note_as_authenticated_user()
    {
        $user = User::factory()
            ->has(Note::factory())
            ->create();

        Sanctum::actingAs($user, ['*']);

        $note = $user->notes->first();

        $response = $this->deleteJson('/api/v1/notes/' . $note->id)
            ->assertSee([
                'Successfully deleted the note'
            ]);

        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
            'user_id' => $user->id,
            'details' => $note->details
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_cannot_delete_note_for_other_user()
    {
        $userWithNote = User::factory()
            ->has(Note::factory())
            ->create();

        $authenticatedUser = User::factory()->create();

        Sanctum::actingAs($authenticatedUser, ['*']);

        $note = $userWithNote->notes->first();

        $response = $this->deleteJson('/api/v1/notes/' . $note->id);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'user_id' => $userWithNote->id,
            'details' => $note->details
        ]);

        $response->assertStatus(403);
    }
}
