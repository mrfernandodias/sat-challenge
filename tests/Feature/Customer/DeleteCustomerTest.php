<?php

use App\Models\Customer;

use function Pest\Laravel\deleteJson;

describe('DELETE /customers/{id}', function () {
  it('deletes an existing customer', function () {
    $customer = Customer::factory()->create();

    deleteJson("/customers/{$customer->id}")
      ->assertStatus(200)
      ->assertJson([
        'success' => true,
        'message' => 'Cliente excluído com sucesso.',
      ]);

    expect(Customer::find($customer->id))->toBeNull();
  });

  it('returns 404 for non-existent customer', function () {
    deleteJson('/customers/99999')
      ->assertStatus(404);
  });

  it('does not affect other customers when deleting', function () {
    $customer1 = Customer::factory()->create();
    $customer2 = Customer::factory()->create();
    $customer3 = Customer::factory()->create();

    deleteJson("/customers/{$customer2->id}")
      ->assertStatus(200);

    expect(Customer::find($customer1->id))->not->toBeNull();
    expect(Customer::find($customer2->id))->toBeNull();
    expect(Customer::find($customer3->id))->not->toBeNull();
  });
});
