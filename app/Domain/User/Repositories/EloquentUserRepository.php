<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\DTOs\UserDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
  public function __construct(
    protected User $model
  ) {}

  public function all(): Collection
  {
    return $this->model->newQuery()
      ->orderBy('id', 'desc')
      ->get();
  }

  public function find(int $id): ?User
  {
    return $this->model->find($id);
  }

  public function findOrFail(int $id): User
  {
    return $this->model->findOrFail($id);
  }

  public function create(UserDTO $dto): User
  {
    return $this->model->create($dto->toArray());
  }

  public function update(User $user, UserDTO $dto): User
  {
    $user->update($dto->toArray());

    return $user->fresh();
  }

  public function delete(User $user): bool
  {
    return $user->delete();
  }
}
