<?php

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('pushes bill image automatically when order moves to served', function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    Http::fake([
        '*' => Http::response(['ok' => true], 200),
    ]);

    config([
        'whatsapp.bot_notify_url' => 'https://bot.example.com/notify',
        'whatsapp.bot_notify_secret' => 'secret',
    ]);

    $restaurant = Restaurant::create([
        'name' => 'Test Grill',
        'location' => 'Cape Town',
        'phone' => '0800000000',
        'is_active' => true,
    ]);

    $manager = User::factory()->create(['restaurant_id' => $restaurant->id]);
    $manager->assignRole('manager');

    $order = Order::create([
        'restaurant_id' => $restaurant->id,
        'table_number' => 'T1',
        'status' => 'preparing',
        'total_amount' => 5000,
        'whatsapp_jid' => '27712345678@s.whatsapp.net',
        'customer_phone' => '27712345678',
    ]);

    $this->actingAs($manager)
        ->put(route('manager.orders.update', $order), ['status' => 'served'])
        ->assertRedirect();

    Http::assertSent(fn ($request) => str_contains($request->url(), 'bot.example.com'));
    expect($order->fresh()->bill_image_pushed_at)->not->toBeNull();
});
