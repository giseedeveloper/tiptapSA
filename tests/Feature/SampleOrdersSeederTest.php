<?php

use App\Models\Order;
use App\Models\Payment;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SampleOrdersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('sample orders seeder creates venue waiter orders and payments', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->seed(SampleOrdersSeeder::class);

    expect(Order::withoutGlobalScopes()->where('notes', 'tiptap_sample_seed')->count())->toBeGreaterThan(10);
    expect(Payment::query()->whereIn('status', ['paid', 'completed'])->count())->toBeGreaterThan(3);

    $this->seed(SampleOrdersSeeder::class);

    expect(Order::withoutGlobalScopes()->where('notes', 'tiptap_sample_seed')->count())->toBeGreaterThan(10);
});
