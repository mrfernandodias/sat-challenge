<?php

use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
  $this->user = User::factory()->create();

  $this->validData = [
    'name' => 'João da Silva',
    'phone' => '(11) 99999-9999',
    'cpf' => '123.456.789-00',
    'email' => 'joao@example.com',
    'cep' => '01310-100',
    'street' => 'Avenida Paulista',
    'neighborhood' => 'Bela Vista',
    'number' => '1000',
    'complement' => 'Sala 101',
    'city' => 'São Paulo',
    'state' => 'SP',
  ];
});

describe('API Authentication', function () {
  it('requires authentication for all endpoints', function () {
    getJson('/api/customers')->assertStatus(401);
    postJson('/api/customers', [])->assertStatus(401);
    getJson('/api/customers/1')->assertStatus(401);
    putJson('/api/customers/1', [])->assertStatus(401);
    deleteJson('/api/customers/1')->assertStatus(401);
  });
});

describe('GET /api/customers', function () {
  it('lists all customers', function () {
    Customer::factory()->count(3)->create();

    $this->actingAs($this->user, 'sanctum')
      ->getJson('/api/customers')
      ->assertStatus(200)
      ->assertJsonCount(3, 'data');
  });

  it('returns empty data when no customers exist', function () {
    $this->actingAs($this->user, 'sanctum')
      ->getJson('/api/customers')
      ->assertStatus(200)
      ->assertJson(['data' => []]);
  });
});

describe('GET /api/customers/{id}', function () {
  it('returns customer details', function () {
    $customer = Customer::factory()->create(['name' => 'Maria Santos']);

    $this->actingAs($this->user, 'sanctum')
      ->getJson("/api/customers/{$customer->id}")
      ->assertStatus(200)
      ->assertJsonPath('data.name', 'Maria Santos');
  });

  it('returns 404 for non-existent customer', function () {
    $this->actingAs($this->user, 'sanctum')
      ->getJson('/api/customers/99999')
      ->assertStatus(404);
  });
});

describe('POST /api/customers', function () {
  it('creates a customer with valid data', function () {
    $this->actingAs($this->user, 'sanctum')
      ->postJson('/api/customers', $this->validData)
      ->assertStatus(201)
      ->assertJsonPath('message', 'Cliente criado com sucesso.')
      ->assertJsonPath('data.name', 'João da Silva');

    expect(Customer::where('cpf', '123.456.789-00')->exists())->toBeTrue();
  });

  it('validates required fields', function () {
    $this->actingAs($this->user, 'sanctum')
      ->postJson('/api/customers', [])
      ->assertStatus(422)
      ->assertJsonValidationErrors(['name', 'phone', 'cpf', 'email']);
  });

  it('validates unique cpf', function () {
    Customer::factory()->create(['cpf' => '123.456.789-00']);

    $this->actingAs($this->user, 'sanctum')
      ->postJson('/api/customers', $this->validData)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['cpf']);
  });
});

describe('PUT /api/customers/{id}', function () {
  it('updates a customer with valid data', function () {
    $customer = Customer::factory()->create(['cpf' => '111.111.111-11']);

    $data = $this->validData;
    $data['cpf'] = '111.111.111-11';

    $this->actingAs($this->user, 'sanctum')
      ->putJson("/api/customers/{$customer->id}", $data)
      ->assertStatus(200)
      ->assertJsonPath('message', 'Cliente atualizado com sucesso.')
      ->assertJsonPath('data.name', 'João da Silva');
  });

  it('returns 404 for non-existent customer', function () {
    $this->actingAs($this->user, 'sanctum')
      ->putJson('/api/customers/99999', $this->validData)
      ->assertStatus(404);
  });
});

describe('DELETE /api/customers/{id}', function () {
  it('deletes a customer', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($this->user, 'sanctum')
      ->deleteJson("/api/customers/{$customer->id}")
      ->assertStatus(200)
      ->assertJsonPath('message', 'Cliente excluído com sucesso.');

    expect(Customer::find($customer->id))->toBeNull();
  });

  it('returns 404 for non-existent customer', function () {
    $this->actingAs($this->user, 'sanctum')
      ->deleteJson('/api/customers/99999')
      ->assertStatus(404);
  });
});
