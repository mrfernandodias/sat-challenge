<?php

namespace App\Domain\Customer\Repositories;

use App\Domain\Customer\DTOs\CustomerDTO;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryInterface
{
  public function all(): Collection;

  public function find(int $id): ?Customer;

  public function findOrFail(int $id): Customer;

  public function create(CustomerDTO $dto): Customer;

  public function update(Customer $customer, CustomerDTO $dto): Customer;

  public function delete(Customer $customer): bool;

  public function paginate(int $perPage = 15);

  public function search(string $term): Collection;
}
