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
        ->assertSee('TIPTAP Assistant');
});

test('guest can register restaurant and manager account', function () {
    $email = 'newmanager'.uniqid().'@example.com';

    $response = $this->post(route('restaurant.register.store'), [
        'restaurant_name' => 'Sunset Bistro',
        'location' => 'Sandton, Johannesburg',
        'phone' => '0712345678',
        'manager_name' => 'Jane Doe',
        'manager_email' => $email,
        'manager_password' => 'SecurePass123!',
        'manager_password_confirmation' => 'SecurePass123!',
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

    $this->post(route('restaurant.register.store'), [
        'restaurant_name' => 'Test Cafe',
        'location' => 'Durban',
        'phone' => '0820000000',
        'manager_name' => 'Bob',
        'manager_email' => $email,
        'manager_password' => 'password123',
        'manager_password_confirmation' => 'password123',
    ])->assertRedirect(route('manager.onboarding.waiting'));

    expect(User::where('email', $email)->first()?->hasRole('manager'))->toBeTrue();
});
