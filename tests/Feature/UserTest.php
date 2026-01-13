<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
  $this->user = User::factory()->create();
  $this->actingAs($this->user);
});

describe('User Index', function () {
  it('displays users page', function () {
    $response = $this->get('/users');

    $response->assertStatus(200);
    $response->assertSee('Usuários');
    $response = $this->get('/users/data');

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        '*' => ['id', 'name', 'email', 'created_at', 'updated_at'],
      ],
    ]);
  });
});

describe('User Create', function () {
  it('creates a new user', function () {
    $response = $this->post('/users', [
      'name' => 'Novo Usuário',
      'email' => 'novo@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201);
    $response->assertJsonFragment(['message' => 'Usuário criado com sucesso.']);

    $this->assertDatabaseHas('users', [
      'name' => 'Novo Usuário',
      'email' => 'novo@example.com',
    ]);
  });

  it('validates required fields on create', function () {
    $response = $this->postJson('/users', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'password']);
  });

  it('validates unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->postJson('/users', [
      'name' => 'Novo Usuário',
      'email' => 'existing@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
  });

  it('validates password confirmation', function () {
    $response = $this->postJson('/users', [
      'name' => 'Novo Usuário',
      'email' => 'novo@example.com',
      'password' => 'password123',
      'password_confirmation' => 'differentpassword',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['password']);
  });

  it('validates minimum password length', function () {
    $response = $this->postJson('/users', [
      'name' => 'Novo Usuário',
      'email' => 'novo@example.com',
      'password' => '123',
      'password_confirmation' => '123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['password']);
  });
});

describe('User Update', function () {
  it('updates an existing user', function () {
    $targetUser = User::factory()->create();

    $response = $this->put("/users/{$targetUser->id}", [
      'name' => 'Usuário Atualizado',
      'email' => 'atualizado@example.com',
    ]);

    $response->assertStatus(200);
    $response->assertJsonFragment(['message' => 'Usuário atualizado com sucesso.']);

    $this->assertDatabaseHas('users', [
      'id' => $targetUser->id,
      'name' => 'Usuário Atualizado',
      'email' => 'atualizado@example.com',
    ]);
  });

  it('updates user password when provided', function () {
    $targetUser = User::factory()->create();
    $oldPasswordHash = $targetUser->password;

    $response = $this->put("/users/{$targetUser->id}", [
      'name' => $targetUser->name,
      'email' => $targetUser->email,
      'password' => 'newpassword123',
      'password_confirmation' => 'newpassword123',
    ]);

    $response->assertStatus(200);

    $targetUser->refresh();
    expect($targetUser->password)->not->toBe($oldPasswordHash);
  });

  it('keeps existing password when not provided on update', function () {
    $targetUser = User::factory()->create();
    $oldPasswordHash = $targetUser->password;

    $response = $this->put("/users/{$targetUser->id}", [
      'name' => 'Novo Nome',
      'email' => $targetUser->email,
    ]);

    $response->assertStatus(200);

    $targetUser->refresh();
    expect($targetUser->password)->toBe($oldPasswordHash);
  });

  it('validates unique email on update excluding self', function () {
    $targetUser = User::factory()->create();
    $otherUser = User::factory()->create(['email' => 'other@example.com']);

    $response = $this->putJson("/users/{$targetUser->id}", [
      'name' => $targetUser->name,
      'email' => 'other@example.com',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
  });

  it('allows keeping same email on update', function () {
    $targetUser = User::factory()->create(['email' => 'same@example.com']);

    $response = $this->put("/users/{$targetUser->id}", [
      'name' => 'Nome Atualizado',
      'email' => 'same@example.com',
    ]);

    $response->assertStatus(200);
  });

  it('returns 404 for non-existent user', function () {
    $response = $this->put('/users/99999', [
      'name' => 'Test',
      'email' => 'test@example.com',
    ]);

    $response->assertStatus(404);
  });
});

describe('User Delete', function () {
  it('deletes an existing user', function () {
    $targetUser = User::factory()->create();

    $response = $this->delete("/users/{$targetUser->id}");

    $response->assertStatus(200);
    $response->assertJsonFragment(['message' => 'Usuário excluído com sucesso.']);

    $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
  });

  it('prevents user from deleting themselves', function () {
    $response = $this->delete("/users/{$this->user->id}");

    $response->assertStatus(403);
    $response->assertJsonFragment(['message' => 'Você não pode excluir seu próprio usuário.']);

    $this->assertDatabaseHas('users', ['id' => $this->user->id]);
  });

  it('returns 404 for non-existent user', function () {
    $response = $this->delete('/users/99999');

    $response->assertStatus(404);
  });
});
