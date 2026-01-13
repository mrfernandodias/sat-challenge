<?php

use App\Models\Customer;
use App\Models\User;

beforeEach(function () {
  $this->user = User::factory()->create();
  $this->actingAs($this->user);
});

describe('Dashboard', function () {
  it('displays dashboard page', function () {
    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('dashboard');
  });

  it('displays stats on dashboard', function () {
    Customer::factory()->count(5)->create([
      'created_by' => $this->user->id,
    ]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('stats');
    $response->assertViewHas('customersByState');
    $response->assertViewHas('latestCustomers');
    $response->assertViewHas('customersByMonth');
  });

  it('shows correct total customers count', function () {
    Customer::factory()->count(3)->create([
      'created_by' => $this->user->id,
    ]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $stats = $response->viewData('stats');
    expect($stats['total'])->toBe(3);
  });

  it('shows customers created today', function () {
    Customer::factory()->count(2)->create([
      'created_by' => $this->user->id,
      'created_at' => now(),
    ]);

    Customer::factory()->create([
      'created_by' => $this->user->id,
      'created_at' => now()->subDays(5),
    ]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $stats = $response->viewData('stats');
    expect($stats['today'])->toBe(2);
  });

  it('shows customers created this month', function () {
    Customer::factory()->count(3)->create([
      'created_by' => $this->user->id,
      'created_at' => now(),
    ]);

    Customer::factory()->create([
      'created_by' => $this->user->id,
      'created_at' => now()->subMonth(),
    ]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $stats = $response->viewData('stats');
    expect($stats['thisMonth'])->toBe(3);
  });

  it('shows distinct states count', function () {
    Customer::factory()->create([
      'created_by' => $this->user->id,
      'state' => 'SP',
    ]);

    Customer::factory()->create([
      'created_by' => $this->user->id,
      'state' => 'RJ',
    ]);

    Customer::factory()->create([
      'created_by' => $this->user->id,
      'state' => 'SP', // duplicate state
    ]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $stats = $response->viewData('stats');
    expect($stats['states'])->toBe(2);
  });

  it('shows latest customers', function () {
    $oldCustomer = Customer::factory()->create([
      'created_by' => $this->user->id,
      'created_at' => now()->subDays(10),
    ]);

    $newCustomer = Customer::factory()->create([
      'created_by' => $this->user->id,
      'created_at' => now(),
    ]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $latestCustomers = $response->viewData('latestCustomers');
    expect($latestCustomers->first()->id)->toBe($newCustomer->id);
  });

  it('shows customers grouped by state', function () {
    Customer::factory()->count(3)->create([
      'created_by' => $this->user->id,
      'state' => 'SP',
    ]);

    Customer::factory()->count(2)->create([
      'created_by' => $this->user->id,
      'state' => 'RJ',
    ]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $customersByState = $response->viewData('customersByState');
    expect($customersByState['SP'])->toBe(3);
    expect($customersByState['RJ'])->toBe(2);
  });
});
