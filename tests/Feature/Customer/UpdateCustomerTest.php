<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

beforeEach(function () {
  $this->user = User::factory()->create();
  $this->actingAs($this->user);

  $this->customer = Customer::factory()->create([
    'name' => 'Nome Original',
    'cpf' => '111.111.111-11',
  ]);

  $this->validData = [
    'name' => 'Nome Atualizado',
    'phone' => '(11) 88888-8888',
    'cpf' => '111.111.111-11',
    'email' => 'atualizado@example.com',
    'cep' => '01310-200',
    'street' => 'Rua Nova',
    'neighborhood' => 'Bairro Novo',
    'number' => '500',
    'complement' => 'Apto 10',
    'city' => 'São Paulo',
    'state' => 'SP',
  ];
});

describe('PUT /customers/{id}', function () {
  it('updates a customer with valid data', function () {
    putJson("/customers/{$this->customer->id}", $this->validData)
      ->assertStatus(200)
      ->assertJson([
        'success' => true,
        'message' => 'Cliente atualizado com sucesso.',
      ]);

    $this->customer->refresh();
    expect($this->customer->name)->toBe('Nome Atualizado');
    expect($this->customer->email)->toBe('atualizado@example.com');
  });

  it('returns updated customer data in response', function () {
    $response = putJson("/customers/{$this->customer->id}", $this->validData);

    $response->assertJsonPath('customer.name', 'Nome Atualizado');
    $response->assertJsonPath('customer.email', 'atualizado@example.com');
  });

  it('allows keeping the same cpf on update', function () {
    putJson("/customers/{$this->customer->id}", $this->validData)
      ->assertStatus(200);

    $this->customer->refresh();
    expect($this->customer->cpf)->toBe('111.111.111-11');
  });

  it('updates only provided fields', function () {
    $originalPhone = $this->customer->phone;

    $data = $this->validData;
    $data['phone'] = $originalPhone;

    putJson("/customers/{$this->customer->id}", $data)
      ->assertStatus(200);

    $this->customer->refresh();
    expect($this->customer->phone)->toBe($originalPhone);
  });
});

describe('PUT /customers/{id} - validation errors', function () {
  it('requires name field', function () {
    $data = $this->validData;
    unset($data['name']);

    putJson("/customers/{$this->customer->id}", $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['name']);
  });

  it('requires valid email format', function () {
    $data = $this->validData;
    $data['email'] = 'invalid-email';

    putJson("/customers/{$this->customer->id}", $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['email']);
  });

  it('prevents duplicate cpf from another customer', function () {
    $otherCustomer = Customer::factory()->create(['cpf' => '222.222.222-22']);

    $data = $this->validData;
    $data['cpf'] = '222.222.222-22';

    putJson("/customers/{$this->customer->id}", $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['cpf']);
  });

  it('requires state with exactly 2 characters', function () {
    $data = $this->validData;
    $data['state'] = 'São Paulo';

    putJson("/customers/{$this->customer->id}", $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['state']);
  });
});

describe('PUT /customers/{id} - not found', function () {
  it('returns 404 for non-existent customer', function () {
    putJson('/customers/99999', $this->validData)
      ->assertStatus(404);
  });
});
