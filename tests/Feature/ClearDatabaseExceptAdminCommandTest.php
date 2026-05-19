<?php

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()->create(['email' => 'admin@taptap.com']);
    $this->admin->assignRole('super_admin');

    $this->restaurant = Restaurant::create([
        'name' => 'Test Grill',
        'location' => 'Cape Town',
        'phone' => '0800000000',
    ]);

    $this->manager = User::factory()->create([
        'email' => 'manager@taptap.com',
        'restaurant_id' => $this->restaurant->id,
    ]);
    $this->manager->assignRole('manager');
});

test('command fails when no super admin exists', function () {
    $this->admin->syncRoles([]);

    $this->artisan('db:clear-except-admin', ['--force' => true])
        ->assertFailed();
});

test('command removes all data except super admin', function () {
    $this->artisan('db:clear-except-admin', ['--force' => true])
        ->assertSuccessful();

    expect(User::count())->toBe(1);
    expect(User::first()->email)->toBe('admin@taptap.com');
    expect(User::first()->hasRole('super_admin'))->toBeTrue();
    expect(User::first()->restaurant_id)->toBeNull();
    expect(Restaurant::count())->toBe(0);
});

test('command requires confirmation without force flag', function () {
    $this->artisan('db:clear-except-admin')
        ->expectsConfirmation('Continue?', 'no')
        ->assertSuccessful();

    expect(User::count())->toBe(2);
    expect(Restaurant::count())->toBe(1);
});
