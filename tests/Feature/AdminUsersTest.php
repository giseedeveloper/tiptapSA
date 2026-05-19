<?php

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('super_admin');

    $this->restaurant = Restaurant::create([
        'name' => 'Test Grill',
        'location' => 'Johannesburg',
        'phone' => '0800000000',
        'is_active' => true,
    ]);
});

test('super admin can view and filter users index', function () {
    $manager = User::factory()->create([
        'restaurant_id' => $this->restaurant->id,
        'name' => 'Jane Manager',
    ]);
    $manager->assignRole('manager');

    $this->actingAs($this->admin)
        ->get(route('admin.users.index', ['role' => 'manager', 'q' => 'Jane']))
        ->assertOk()
        ->assertViewIs('admin.users.index')
        ->assertSee('Jane Manager');
});

test('user create route is not registered', function () {
    expect(Route::has('admin.users.create'))->toBeFalse();
});

test('super admin can update user role and restaurant', function () {
    $user = User::factory()->create([
        'name' => 'Bob Waiter',
        'email' => 'bob@example.com',
    ]);
    $user->assignRole('waiter');

    $this->actingAs($this->admin)
        ->put(route('admin.users.update', $user), [
            'name' => 'Bob Waiter',
            'email' => 'bob@example.com',
            'role' => 'manager',
            'restaurant_id' => $this->restaurant->id,
        ])
        ->assertRedirect(route('admin.users.index'));

    $user->refresh();
    expect($user->restaurant_id)->toBe($this->restaurant->id);
    expect($user->hasRole('manager'))->toBeTrue();
});

test('super admin cannot delete themselves', function () {
    $this->actingAs($this->admin)
        ->delete(route('admin.users.destroy', $this->admin))
        ->assertRedirect();

    expect(User::find($this->admin->id))->not->toBeNull();
});

test('manager cannot access admin users', function () {
    $manager = User::factory()->create(['restaurant_id' => $this->restaurant->id]);
    $manager->assignRole('manager');

    $this->actingAs($manager)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});
