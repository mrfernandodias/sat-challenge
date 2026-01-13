<?php

use App\Models\User;

use function Pest\Laravel\postJson;

describe('POST /api/login', function () {
  it('authenticates user with valid credentials', function () {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    postJson('/api/login', [
      'email' => 'test@example.com',
      'password' => 'password123',
      'device_name' => 'test-device',
    ])
      ->assertStatus(200)
      ->assertJsonStructure([
        'message',
        'token',
        'user' => ['id', 'name', 'email'],
      ]);
  });

  it('fails with invalid credentials', function () {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    postJson('/api/login', [
      'email' => 'test@example.com',
      'password' => 'wrong-password',
      'device_name' => 'test-device',
    ])
      ->assertStatus(422)
      ->assertJsonValidationErrors(['email']);
  });

  it('fails with non-existent user', function () {
    postJson('/api/login', [
      'email' => 'nonexistent@example.com',
      'password' => 'password123',
      'device_name' => 'test-device',
    ])
      ->assertStatus(422)
      ->assertJsonValidationErrors(['email']);
  });

  it('requires all fields', function () {
    postJson('/api/login', [])
      ->assertStatus(422)
      ->assertJsonValidationErrors(['email', 'password', 'device_name']);
  });
});

describe('POST /api/logout', function () {
  it('revokes current token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-device')->plainTextToken;

    $this->withHeader('Authorization', "Bearer {$token}")
      ->postJson('/api/logout')
      ->assertStatus(200)
      ->assertJson(['message' => 'Logout realizado com sucesso.']);

    expect($user->tokens()->count())->toBe(0);
  });

  it('requires authentication', function () {
    postJson('/api/logout')
      ->assertStatus(401);
  });
});

describe('POST /api/logout-all', function () {
  it('revokes all tokens', function () {
    $user = User::factory()->create();
    $user->createToken('device-1');
    $user->createToken('device-2');

    $this->actingAs($user, 'sanctum')
      ->postJson('/api/logout-all')
      ->assertStatus(200)
      ->assertJson(['message' => 'Todos os tokens foram revogados.']);

    expect($user->tokens()->count())->toBe(0);
  });
});
