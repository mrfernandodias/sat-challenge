<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
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
      'full_address' => $this->fullAddress(),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'creator' => $this->whenLoaded('creator', fn() => [
        'id' => $this->creator->id,
        'name' => $this->creator->name,
      ]),
    ];
  }

  protected function fullAddress(): ?string
  {
    $parts = array_filter([
      $this->street,
      $this->number,
      $this->complement,
      $this->neighborhood,
      $this->city,
      $this->state,
    ]);

    return $parts ? implode(', ', $parts) : null;
  }
}
