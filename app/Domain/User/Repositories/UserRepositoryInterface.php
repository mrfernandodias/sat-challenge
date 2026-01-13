<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\DTOs\UserDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
  public function all(): Collection;

  public function find(int $id): ?User;

  public function findOrFail(int $id): User;

  public function create(UserDTO $dto): User;

  public function update(User $user, UserDTO $dto): User;

  public function delete(User $user): bool;
}
