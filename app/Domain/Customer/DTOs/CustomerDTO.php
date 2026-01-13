<?php

namespace App\Domain\Customer\DTOs;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

readonly class CustomerDTO
{
  public function __construct(
    public string $name,
    public ?string $phone = null,
    public ?string $cpf = null,
    public ?string $email = null,
    public ?string $cep = null,
    public ?string $street = null,
    public ?string $neighborhood = null,
    public ?string $number = null,
    public ?string $complement = null,
    public ?string $city = null,
    public ?string $state = null,
    public ?int $createdBy = null,
  ) {}

  public static function fromStoreRequest(StoreCustomerRequest $request): self
  {
    return new self(
      name: $request->validated('name'),
      phone: $request->validated('phone'),
      cpf: $request->validated('cpf'),
      email: $request->validated('email'),
      cep: $request->validated('cep'),
      street: $request->validated('street'),
      neighborhood: $request->validated('neighborhood'),
      number: $request->validated('number'),
      complement: $request->validated('complement'),
      city: $request->validated('city'),
      state: $request->validated('state'),
      createdBy: auth()->id(),
    );
  }

  public static function fromUpdateRequest(UpdateCustomerRequest $request): self
  {
    return new self(
      name: $request->validated('name'),
      phone: $request->validated('phone'),
      cpf: $request->validated('cpf'),
      email: $request->validated('email'),
      cep: $request->validated('cep'),
      street: $request->validated('street'),
      neighborhood: $request->validated('neighborhood'),
      number: $request->validated('number'),
      complement: $request->validated('complement'),
      city: $request->validated('city'),
      state: $request->validated('state'),
    );
  }

  public function toArray(): array
  {
    return array_filter([
      'name' => $this->name,
      'phone' => $this->phone,
      'cpf' => $this->cpf,
      'email' => $this->email,
      'cep' => $this->cep,
      'street' => $this->street,
      'neighborhood' => $this->neighborhood,
      'number' => $this->number,
      'complement' => $this->complement,
      'city' => $this->city,
      'state' => $this->state,
      'created_by' => $this->createdBy,
    ], fn($value) => $value !== null);
  }
}
