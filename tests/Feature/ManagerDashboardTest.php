<?php

use App\Models\Feedback;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    $this->restaurant = Restaurant::create([
        'name' => 'Test Grill',
        'location' => 'Johannesburg',
        'phone' => '0800000000',
        'is_active' => true,
    ]);

    $this->manager = User::factory()->create([
        'restaurant_id' => $this->restaurant->id,
    ]);
    $this->manager->assignRole('manager');
});

test('manager can view dashboard', function () {
    $response = $this->actingAs($this->manager)->get(route('manager.dashboard'));

    $response->assertOk();
    $response->assertViewIs('manager.dashboard');
    $response->assertSee('Manager Dashboard');
    $response->assertSee('Quick Actions');
    $response->assertSee('Live Orders');
});

test('manager dashboard stats api returns accurate counts', function () {
    $waiter = User::factory()->create([
        'restaurant_id' => $this->restaurant->id,
        'is_online' => true,
    ]);
    $waiter->assignRole('waiter');

    Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T1',
        'status' => 'paid',
        'total_amount' => 50000,
        'created_at' => now(),
    ]);

    Feedback::withoutGlobalScopes()->create([
        'restaurant_id' => $this->restaurant->id,
        'order_id' => Order::first()->id,
        'waiter_id' => $waiter->id,
        'rating' => 4,
        'comment' => 'Great service',
    ]);

    $response = $this->actingAs($this->manager)->getJson(route('manager.dashboard.stats'));

    $response->assertOk();
    $response->assertJson([
        'total_orders_today' => 1,
        'revenue_today' => 50000,
        'avg_rating' => '4.0',
        'waiters_online' => 1,
    ]);
});

test('manager dashboard stats use paid order totals when no payment row exists', function () {
    Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T2',
        'status' => 'paid',
        'total_amount' => 7000,
        'created_at' => now(),
    ]);

    $response = $this->actingAs($this->manager)->getJson(route('manager.dashboard.stats'));

    $response->assertOk();
    $response->assertJson([
        'total_orders_today' => 1,
        'revenue_today' => 7000,
    ]);
});

test('manager dashboard analytics api returns chart data', function () {
    Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T3',
        'status' => 'paid',
        'total_amount' => 12000,
        'created_at' => now(),
    ]);

    $response = $this->actingAs($this->manager)->getJson(route('manager.dashboard.analytics'));

    $response->assertOk();
    $response->assertJsonStructure([
        'weekly_trend',
        'hourly_activity',
        'week_comparison',
        'insights',
    ]);
    expect($response->json('weekly_trend'))->toHaveCount(7);
    expect($response->json('hourly_activity'))->toHaveCount(24);
});

test('guest is redirected from manager dashboard', function () {
    $this->get(route('manager.dashboard'))
        ->assertRedirect(route('login'));
});

test('waiter cannot access manager dashboard', function () {
    $waiter = User::factory()->create(['restaurant_id' => $this->restaurant->id]);
    $waiter->assignRole('waiter');

    $this->actingAs($waiter)
        ->get(route('manager.dashboard'))
        ->assertForbidden();
});
