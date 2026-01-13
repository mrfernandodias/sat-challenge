<?php

use App\Models\Customer;

use function Pest\Laravel\getJson;

describe('GET /customers/data', function () {
  it('returns empty data when no customers exist', function () {
    getJson('/customers/data')
      ->assertStatus(200)
      ->assertJson([
        'data' => [],
      ]);
  });

  it('returns all customers', function () {
    Customer::factory()->count(3)->create();

    $response = getJson('/customers/data');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(3);
  });

  it('returns customer data with correct structure', function () {
    Customer::factory()->create([
      'name' => 'Maria Santos',
      'email' => 'maria@example.com',
      'city' => 'Rio de Janeiro',
      'state' => 'RJ',
    ]);

    getJson('/customers/data')
      ->assertStatus(200)
      ->assertJsonStructure([
        'data' => [
          '*' => [
            'id',
            'name',
            'phone',
            'cpf',
            'email',
            'cep',
            'street',
            'neighborhood',
            'number',
            'city',
            'state',
            'full_address',
            'created_at',
            'updated_at',
          ],
        ],
      ]);
  });

  it('returns customer with full_address computed', function () {
    Customer::factory()->create([
      'street' => 'Rua das Flores',
      'number' => '123',
      'neighborhood' => 'Centro',
      'city' => 'São Paulo',
      'state' => 'SP',
    ]);

    $response = getJson('/customers/data');

    expect($response->json('data.0.full_address'))->toContain('Rua das Flores');
    expect($response->json('data.0.full_address'))->toContain('123');
    expect($response->json('data.0.full_address'))->toContain('São Paulo');
  });
});

describe('GET /customers (view)', function () {
  it('returns the customers view', function () {
    $response = $this->get('/customers');

    $response->assertStatus(200);
    $response->assertViewIs('customers');
  });
});
