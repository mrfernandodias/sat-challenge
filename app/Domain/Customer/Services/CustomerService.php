<?php

namespace App\Domain\Customer\Services;

use App\Domain\Customer\DTOs\CustomerDTO;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerService
{
  public function __construct(
    protected CustomerRepositoryInterface $repository
  ) {}

  public function getAllCustomers(): Collection
  {
    return $this->repository->all();
  }

  public function getCustomerById(int $id): ?Customer
  {
    return $this->repository->find($id);
  }

  public function createCustomer(CustomerDTO $dto): Customer
  {
    return $this->repository->create($dto);
  }

  public function updateCustomer(Customer $customer, CustomerDTO $dto): Customer
  {
    return $this->repository->update($customer, $dto);
  }

  public function deleteCustomer(Customer $customer): bool
  {
    return $this->repository->delete($customer);
  }

  public function searchCustomers(string $term): Collection
  {
    return $this->repository->search($term);
  }

  public function getPaginatedCustomers(int $perPage = 15)
  {
    return $this->repository->paginate($perPage);
  }
}
