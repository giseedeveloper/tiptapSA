<?php

use App\Models\Order;
use App\Models\Payment;
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

test('super admin can view orders index with filters', function () {
    Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T1',
        'status' => 'paid',
        'total_amount' => 5000,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.orders.index', ['status' => 'paid', 'restaurant_id' => $this->restaurant->id]))
        ->assertOk()
        ->assertViewIs('admin.orders.index')
        ->assertSee('Order Management');
});

test('order create and edit routes are not registered', function () {
    expect(Route::has('admin.orders.create'))->toBeFalse();
    expect(Route::has('admin.orders.edit'))->toBeFalse();
});

test('order show displays payment details', function () {
    $order = Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T3',
        'status' => 'served',
        'total_amount' => 8000,
    ]);

    Payment::create([
        'order_id' => $order->id,
        'amount' => 8000,
        'method' => 'cash',
        'status' => 'paid',
        'transaction_reference' => 'TXN-12345',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.orders.show', $order))
        ->assertOk()
        ->assertSee('TXN-12345')
        ->assertSee('CASH')
        ->assertSee('paid');
});

test('super admin can update order status including served and paid', function () {
    $order = Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T4',
        'status' => 'preparing',
        'total_amount' => 3000,
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.orders.update', $order), ['status' => 'served'])
        ->assertRedirect(route('admin.orders.index'));

    expect($order->fresh()->status)->toBe('served');
});

test('admin orders export returns csv', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.orders.export'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

test('manager cannot access admin orders', function () {
    $manager = User::factory()->create(['restaurant_id' => $this->restaurant->id]);
    $manager->assignRole('manager');

    $this->actingAs($manager)
        ->get(route('admin.orders.index'))
        ->assertForbidden();
});
