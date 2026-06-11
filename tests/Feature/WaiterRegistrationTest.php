<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
});

test('waiter registration page loads stepped wizard', function () {
    $this->get(route('waiter.register'))
        ->assertOk()
        ->assertSee('Register as Waiter')
        ->assertSee('Step 1 of 4')
        ->assertSee('waiter-registration-form', false);
});

test('guest can register waiter account', function () {
    $email = 'newwaiter'.uniqid().'@example.com';

    $response = $this->post(route('waiter.register.store'), [
        'first_name' => 'Thabo',
        'last_name' => 'Nkosi',
        'email' => $email,
        'phone' => '0712345678',
        'location' => 'Cape Town',
        'password' => 'SecurePass123!',
        'password_confirmation' => 'SecurePass123!',
    ]);

    $response->assertRedirect(route('waiter.dashboard'));

    $user = User::where('email', $email)->first();
    expect($user)->not->toBeNull();
    expect($user->hasRole('waiter'))->toBeTrue();
    expect($user->restaurant_id)->toBeNull();
    expect($user->global_waiter_number)->not->toBeEmpty();
});

test('waiter registration allows optional location', function () {
    $email = 'nowaiterloc'.uniqid().'@example.com';

    $this->post(route('waiter.register.store'), [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => $email,
        'phone' => '0820000000',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('waiter.dashboard'));

    expect(User::where('email', $email)->first()?->location)->toBeNull();
});
