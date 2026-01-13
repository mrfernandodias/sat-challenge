<?php

use App\Domain\Customer\DTOs\CustomerDTO;

describe('CustomerDTO', function () {
  it('creates dto with all properties', function () {
    $dto = new CustomerDTO(
      name: 'João da Silva',
      phone: '(11) 99999-9999',
      cpf: '123.456.789-00',
      email: 'joao@example.com',
      cep: '01310-100',
      street: 'Avenida Paulista',
      neighborhood: 'Bela Vista',
      number: '1000',
      complement: 'Sala 101',
      city: 'São Paulo',
      state: 'SP',
      createdBy: 1,
    );

    expect($dto->name)->toBe('João da Silva');
    expect($dto->phone)->toBe('(11) 99999-9999');
    expect($dto->cpf)->toBe('123.456.789-00');
    expect($dto->email)->toBe('joao@example.com');
    expect($dto->city)->toBe('São Paulo');
    expect($dto->state)->toBe('SP');
    expect($dto->createdBy)->toBe(1);
  });

  it('creates dto with only required properties', function () {
    $dto = new CustomerDTO(name: 'Maria Santos');

    expect($dto->name)->toBe('Maria Santos');
    expect($dto->phone)->toBeNull();
    expect($dto->email)->toBeNull();
  });

  it('converts to array correctly', function () {
    $dto = new CustomerDTO(
      name: 'João da Silva',
      phone: '(11) 99999-9999',
      cpf: '123.456.789-00',
      email: 'joao@example.com',
      cep: '01310-100',
      street: 'Avenida Paulista',
      neighborhood: 'Bela Vista',
      number: '1000',
      city: 'São Paulo',
      state: 'SP',
    );

    $array = $dto->toArray();

    expect($array)->toBeArray();
    expect($array['name'])->toBe('João da Silva');
    expect($array['phone'])->toBe('(11) 99999-9999');
    expect($array['email'])->toBe('joao@example.com');
  });

  it('excludes null values from array', function () {
    $dto = new CustomerDTO(
      name: 'João da Silva',
      email: 'joao@example.com',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKeys(['name', 'email']);
    expect($array)->not->toHaveKey('phone');
    expect($array)->not->toHaveKey('complement');
  });

  it('is immutable (readonly)', function () {
    $dto = new CustomerDTO(name: 'João da Silva');

    expect(fn() => $dto->name = 'Outro Nome')
      ->toThrow(Error::class);
  });
});
