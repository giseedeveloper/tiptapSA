<?php

use App\Jobs\SendBillImageToCustomer;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

beforeEach(function (): void {
    config()->set('whatsapp.bot_notify_url', 'http://bot.test/notify');
    config()->set('whatsapp.bot_notify_secret', 'test-secret');
});

it('dispatches the bill image job when an order transitions to served', function () {
    Queue::fake();

    $restaurant = Restaurant::create([
        'name' => 'Test Cafe',
        'is_active' => true,
    ]);

    $order = Order::withoutGlobalScopes()->create([
        'restaurant_id' => $restaurant->id,
        'table_number' => '5',
        'customer_phone' => '255700000010',
        'whatsapp_jid' => '255700000010@s.whatsapp.net',
        'status' => 'preparing',
        'total_amount' => 5000,
    ]);

    Queue::assertNotPushed(SendBillImageToCustomer::class);

    $order->update(['status' => 'served']);

    Queue::assertPushed(
        SendBillImageToCustomer::class,
        fn (SendBillImageToCustomer $job) => $job->orderId === $order->id
    );
});

it('does not dispatch when the order has no whatsapp jid', function () {
    Queue::fake();

    $restaurant = Restaurant::create([
        'name' => 'Test Cafe 2',
        'is_active' => true,
    ]);

    $order = Order::withoutGlobalScopes()->create([
        'restaurant_id' => $restaurant->id,
        'table_number' => '5',
        'customer_phone' => '255700000011',
        'whatsapp_jid' => null,
        'status' => 'preparing',
        'total_amount' => 5000,
    ]);

    $order->update(['status' => 'served']);

    Queue::assertNothingPushed();
});

it('does not dispatch when status changes between non-served states', function () {
    Queue::fake();

    $restaurant = Restaurant::create([
        'name' => 'Test Cafe 3',
        'is_active' => true,
    ]);

    $order = Order::withoutGlobalScopes()->create([
        'restaurant_id' => $restaurant->id,
        'table_number' => '5',
        'customer_phone' => '255700000012',
        'whatsapp_jid' => '255700000012@s.whatsapp.net',
        'status' => 'pending',
        'total_amount' => 5000,
    ]);

    $order->update(['status' => 'preparing']);

    Queue::assertNothingPushed();
});

it('posts the bill image payload to the bot notify endpoint', function () {
    Http::fake([
        'http://bot.test/notify' => Http::response(['ok' => true], 200),
    ]);

    $restaurant = Restaurant::create([
        'name' => 'Push Cafe',
        'is_active' => true,
    ]);

    $order = Order::withoutGlobalScopes()->create([
        'restaurant_id' => $restaurant->id,
        'table_number' => '9',
        'customer_phone' => '255700000020',
        'whatsapp_jid' => '255700000020@s.whatsapp.net',
        'status' => 'served',
        'total_amount' => 8500,
    ]);

    (new SendBillImageToCustomer($order->id))->handle();

    Http::assertSent(function ($request) use ($order) {
        return $request->url() === 'http://bot.test/notify'
            && $request->header('X-Bot-Secret')[0] === 'test-secret'
            && $request['event'] === 'bill_image'
            && $request['order_id'] === $order->id
            && $request['jid'] === '255700000020@s.whatsapp.net'
            && str_contains($request['bill_image_url'], '/bill-image/'.$order->id)
            && str_contains($request['bill_image_url'], 'signature=');
    });

    expect($order->fresh()->bill_image_pushed_at)->not->toBeNull();
});

it('skips pushing when the bill has already been pushed', function () {
    Http::fake();

    $restaurant = Restaurant::create([
        'name' => 'Idempotent Cafe',
        'is_active' => true,
    ]);

    $order = Order::withoutGlobalScopes()->create([
        'restaurant_id' => $restaurant->id,
        'table_number' => '4',
        'customer_phone' => '255700000030',
        'whatsapp_jid' => '255700000030@s.whatsapp.net',
        'status' => 'served',
        'total_amount' => 3000,
        'bill_image_pushed_at' => now(),
    ]);

    (new SendBillImageToCustomer($order->id))->handle();

    Http::assertNothingSent();
});
