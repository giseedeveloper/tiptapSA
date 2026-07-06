<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
});

test('restaurant registration page loads', function () {
    $this->get(route('restaurant.register'))
        ->assertOk()
        ->assertSee('Register Restaurant')
        ->assertSee('Or register with email');
});

test('restaurant details page requires credentials step first', function () {
    $this->get(route('restaurant.register.details'))
        ->assertRedirect(route('restaurant.register'));
});

test('guest can register restaurant and manager account', function () {
    $email = 'newmanager'.uniqid().'@example.com';

    $this->post(route('restaurant.register.credentials'), [
        'manager_email' => $email,
        'manager_password' => 'SecurePass123!',
        'manager_password_confirmation' => 'SecurePass123!',
    ])->assertRedirect(route('restaurant.register.details'));

    $response = $this->post(route('restaurant.register.details.store'), [
        'manager_name' => 'Jane Doe',
        'restaurant_name' => 'Sunset Bistro',
        'location' => 'Sandton, Johannesburg',
        'phone' => '0712345678',
    ]);

    $response->assertRedirect(route('manager.onboarding.waiting'));

    $this->assertDatabaseHas('restaurants', [
        'name' => 'Sunset Bistro',
        'location' => 'Sandton, Johannesburg',
        'approval_status' => 'pending',
    ]);

    $user = User::where('email', $email)->first();
    expect($user)->not->toBeNull();
    expect($user->hasRole('manager'))->toBeTrue();
    expect($user->restaurant_id)->not->toBeNull();
});

test('registration auto-seeds roles when manager role was missing', function () {
    Role::query()->delete();

    $email = 'autoseed'.uniqid().'@example.com';

    $this->post(route('restaurant.register.credentials'), [
        'manager_email' => $email,
        'manager_password' => 'password123',
        'manager_password_confirmation' => 'password123',
    ])->assertRedirect(route('restaurant.register.details'));

    $this->post(route('restaurant.register.details.store'), [
        'manager_name' => 'Bob',
        'restaurant_name' => 'Test Cafe',
        'location' => 'Durban',
        'phone' => '0820000000',
    ])->assertRedirect(route('manager.onboarding.waiting'));

    expect(User::where('email', $email)->first()?->hasRole('manager'))->toBeTrue();
});
