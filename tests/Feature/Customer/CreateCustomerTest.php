<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

beforeEach(function () {
  $this->user = User::factory()->create();
  $this->actingAs($this->user);

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

describe('POST /customers', function () {
  it('creates a customer with valid data', function () {
    postJson('/customers', $this->validData)
      ->assertStatus(201)
      ->assertJson([
        'success' => true,
        'message' => 'Cliente criado com sucesso.',
      ])
      ->assertJsonStructure([
        'success',
        'message',
        'customer' => [
          'id',
          'name',
          'phone',
          'cpf',
          'email',
          'city',
          'state',
        ],
      ]);

    expect(Customer::where('cpf', '123.456.789-00')->exists())->toBeTrue();
  });

  it('returns customer data in response', function () {
    $response = postJson('/customers', $this->validData);

    $response->assertJsonPath('customer.name', 'João da Silva');
    $response->assertJsonPath('customer.email', 'joao@example.com');
    $response->assertJsonPath('customer.city', 'São Paulo');
  });

  it('creates customer without complement (optional field)', function () {
    $data = $this->validData;
    unset($data['complement']);

    postJson('/customers', $data)
      ->assertStatus(201);

    expect(Customer::where('cpf', '123.456.789-00')->exists())->toBeTrue();
  });
});

describe('POST /customers - validation errors', function () {
  it('requires name field', function () {
    $data = $this->validData;
    unset($data['name']);

    postJson('/customers', $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['name']);
  });

  it('requires phone field', function () {
    $data = $this->validData;
    unset($data['phone']);

    postJson('/customers', $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['phone']);
  });

  it('requires cpf field', function () {
    $data = $this->validData;
    unset($data['cpf']);

    postJson('/customers', $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['cpf']);
  });

  it('requires email field', function () {
    $data = $this->validData;
    unset($data['email']);

    postJson('/customers', $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['email']);
  });

  it('requires valid email format', function () {
    $data = $this->validData;
    $data['email'] = 'invalid-email';

    postJson('/customers', $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['email']);
  });

  it('requires state with exactly 2 characters', function () {
    $data = $this->validData;
    $data['state'] = 'São Paulo';

    postJson('/customers', $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['state']);
  });

  it('requires unique cpf', function () {
    Customer::factory()->create(['cpf' => '123.456.789-00']);

    postJson('/customers', $this->validData)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['cpf']);
  });

  it('requires all address fields', function () {
    $data = $this->validData;
    unset($data['cep'], $data['street'], $data['neighborhood'], $data['number'], $data['city'], $data['state']);

    postJson('/customers', $data)
      ->assertStatus(422)
      ->assertJsonValidationErrors(['cep', 'street', 'neighborhood', 'number', 'city', 'state']);
  });
});
