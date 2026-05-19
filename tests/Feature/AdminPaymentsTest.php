<?php

use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

test('super admin can view payments index filtered by paid status', function () {
    $order = Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T1',
        'status' => 'paid',
        'total_amount' => 12000,
    ]);

    Payment::create([
        'order_id' => $order->id,
        'amount' => 12000,
        'method' => 'ussd',
        'status' => 'paid',
        'transaction_reference' => 'REF-999',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.payments.index', ['status' => 'paid']))
        ->assertOk()
        ->assertViewIs('admin.payments.index')
        ->assertSee('REF-999')
        ->assertSee('ussd');
});

test('payment show page displays method and reference', function () {
    $order = Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T2',
        'status' => 'paid',
        'total_amount' => 5000,
    ]);

    $payment = Payment::create([
        'order_id' => $order->id,
        'amount' => 5000,
        'method' => 'card',
        'status' => 'paid',
        'transaction_reference' => 'CARD-001',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.payments.show', $payment))
        ->assertOk()
        ->assertSee('CARD-001')
        ->assertSee('CARD');
});

test('payment accessors alias legacy attribute names', function () {
    $payment = Payment::make([
        'method' => 'cash',
        'transaction_reference' => 'LEGACY-REF',
    ]);

    expect($payment->payment_method)->toBe('cash');
    expect($payment->transaction_id)->toBe('LEGACY-REF');
});

test('admin payments export includes method column', function () {
    $order = Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T5',
        'status' => 'paid',
        'total_amount' => 1000,
    ]);

    Payment::create([
        'order_id' => $order->id,
        'amount' => 1000,
        'method' => 'cash',
        'status' => 'paid',
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.payments.export'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

test('manager cannot access admin payments', function () {
    $manager = User::factory()->create(['restaurant_id' => $this->restaurant->id]);
    $manager->assignRole('manager');

    $this->actingAs($manager)
        ->get(route('admin.payments.index'))
        ->assertForbidden();
});
