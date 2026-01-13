<?php

namespace App\Domain\User\Services;

use App\Domain\User\DTOs\UserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
  public function __construct(
    protected UserRepositoryInterface $repository
  ) {}

  public function getAllUsers(): Collection
  {
    return $this->repository->all();
  }

  public function getUserById(int $id): ?User
  {
    return $this->repository->find($id);
  }

  public function createUser(UserDTO $dto): User
  {
    return $this->repository->create($dto);
  }

  public function updateUser(User $user, UserDTO $dto): User
  {
    return $this->repository->update($user, $dto);
  }

  public function deleteUser(User $user): bool
  {
    return $this->repository->delete($user);
  }
}
