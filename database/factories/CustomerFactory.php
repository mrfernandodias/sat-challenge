<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
  protected $model = Customer::class;

  public function definition(): array
  {
    return [
      'name' => fake()->name(),
      'phone' => fake()->numerify('(##) #####-####'),
      'cpf' => fake()->unique()->numerify('###.###.###-##'),
      'email' => fake()->unique()->safeEmail(),
      'cep' => fake()->numerify('#####-###'),
      'street' => fake()->streetName(),
      'neighborhood' => fake()->citySuffix() . ' ' . fake()->lastName(),
      'number' => fake()->buildingNumber(),
      'complement' => fake()->optional()->secondaryAddress(),
      'city' => fake()->city(),
      'state' => fake()->randomElement(['SP', 'RJ', 'MG', 'RS', 'PR', 'SC', 'BA', 'PE', 'CE', 'GO']),
    ];
  }
}
