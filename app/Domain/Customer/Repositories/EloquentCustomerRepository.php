<?php

namespace App\Domain\Customer\Repositories;

use App\Domain\Customer\DTOs\CustomerDTO;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{
  public function __construct(
    protected Customer $model
  ) {}

  public function all(): Collection
  {
    return $this->model->newQuery()
      ->orderBy('id', 'desc')
      ->get();
  }

  public function find(int $id): ?Customer
  {
    return $this->model->find($id);
  }

  public function findOrFail(int $id): Customer
  {
    return $this->model->findOrFail($id);
  }

  public function create(CustomerDTO $dto): Customer
  {
    return $this->model->create($dto->toArray());
  }

  public function update(Customer $customer, CustomerDTO $dto): Customer
  {
    $customer->update($dto->toArray());

    return $customer->fresh();
  }

  public function delete(Customer $customer): bool
  {
    return $customer->delete();
  }

  public function paginate(int $perPage = 15)
  {
    return $this->model->newQuery()
      ->orderBy('id', 'desc')
      ->paginate($perPage);
  }

  public function search(string $term): Collection
  {
    return $this->model->newQuery()
      ->where('name', 'like', "%{$term}%")
      ->orWhere('email', 'like', "%{$term}%")
      ->orWhere('cpf', 'like', "%{$term}%")
      ->orWhere('phone', 'like', "%{$term}%")
      ->orderBy('id', 'desc')
      ->get();
  }
}
