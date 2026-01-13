<?php

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

beforeEach(function () {
  $this->request = new Request();
});

describe('CustomerResource', function () {
  it('transforms customer to array with correct structure', function () {
    $customer = Customer::factory()->make([
      'id' => 1,
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
    ]);

    $resource = new CustomerResource($customer);
    $array = $resource->toArray($this->request);

    expect($array)->toHaveKeys([
      'id',
      'name',
      'phone',
      'cpf',
      'email',
      'cep',
      'street',
      'neighborhood',
      'number',
      'complement',
      'city',
      'state',
      'full_address',
      'created_at',
      'updated_at',
    ]);
  });

  it('returns correct values', function () {
    $customer = Customer::factory()->make([
      'name' => 'Maria Santos',
      'email' => 'maria@example.com',
      'city' => 'Rio de Janeiro',
      'state' => 'RJ',
    ]);

    $resource = new CustomerResource($customer);
    $array = $resource->toArray($this->request);

    expect($array['name'])->toBe('Maria Santos');
    expect($array['email'])->toBe('maria@example.com');
    expect($array['city'])->toBe('Rio de Janeiro');
    expect($array['state'])->toBe('RJ');
  });

  it('computes full_address correctly', function () {
    $customer = Customer::factory()->make([
      'street' => 'Rua das Flores',
      'number' => '123',
      'complement' => 'Apto 45',
      'neighborhood' => 'Centro',
      'city' => 'São Paulo',
      'state' => 'SP',
    ]);

    $resource = new CustomerResource($customer);
    $array = $resource->toArray($this->request);

    expect($array['full_address'])->toContain('Rua das Flores');
    expect($array['full_address'])->toContain('123');
    expect($array['full_address'])->toContain('Apto 45');
    expect($array['full_address'])->toContain('Centro');
    expect($array['full_address'])->toContain('São Paulo');
    expect($array['full_address'])->toContain('SP');
  });

  it('handles null complement in full_address', function () {
    $customer = Customer::factory()->make([
      'street' => 'Rua das Flores',
      'number' => '123',
      'complement' => null,
      'neighborhood' => 'Centro',
      'city' => 'São Paulo',
      'state' => 'SP',
    ]);

    $resource = new CustomerResource($customer);
    $array = $resource->toArray($this->request);

    expect($array['full_address'])->not->toContain('null');
    expect($array['full_address'])->toContain('Rua das Flores');
  });

  it('returns null full_address when no address fields', function () {
    $customer = Customer::factory()->make([
      'street' => null,
      'number' => null,
      'complement' => null,
      'neighborhood' => null,
      'city' => null,
      'state' => null,
    ]);

    $resource = new CustomerResource($customer);
    $array = $resource->toArray($this->request);

    expect($array['full_address'])->toBeNull();
  });
});
