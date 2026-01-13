<?php

namespace App\Providers;

use App\Domain\Customer\Repositories\CustomerRepositoryInterface;
use App\Domain\Customer\Repositories\EloquentCustomerRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
  /**
   * All repository bindings.
   */
  protected array $repositories = [
    CustomerRepositoryInterface::class => EloquentCustomerRepository::class,
  ];

  public function register(): void
  {
    foreach ($this->repositories as $interface => $implementation) {
      $this->app->bind($interface, $implementation);
    }
  }

  public function boot(): void
  {
    //
  }
}
