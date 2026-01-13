<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Login', function () {
  it('displays login page', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Faça login para iniciar sua sessão');
  });

  it('allows user to login with valid credentials', function () {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
      'email' => 'test@example.com',
      'password' => 'password123',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
  });

  it('rejects invalid credentials', function () {
    User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
      'email' => 'test@example.com',
      'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
  });

  it('validates required fields', function () {
    $response = $this->post('/login', [
      'email' => '',
      'password' => '',
    ]);

    $response->assertSessionHasErrors(['email', 'password']);
  });

  it('validates email format', function () {
    $response = $this->post('/login', [
      'email' => 'invalid-email',
      'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
  });
});

describe('Logout', function () {
  it('allows user to logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
  });
});

describe('Protected Routes', function () {
  it('redirects unauthenticated user to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
  });

  it('allows authenticated user to access dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
  });

  it('redirects unauthenticated user from customers page', function () {
    $response = $this->get('/customers');

    $response->assertRedirect('/login');
  });

  it('redirects unauthenticated user from users page', function () {
    $response = $this->get('/users');

    $response->assertRedirect('/login');
  });
});
