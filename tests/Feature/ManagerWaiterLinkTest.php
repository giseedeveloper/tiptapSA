<?php

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    $this->restaurant = Restaurant::create([
        'name' => 'Urban Flame',
        'location' => 'Johannesburg',
        'phone' => '0800000000',
        'is_active' => true,
        'tag_prefix' => 'UR1',
    ]);

    $this->otherRestaurant = Restaurant::create([
        'name' => 'Other Grill',
        'location' => 'Cape Town',
        'phone' => '0800000001',
        'is_active' => true,
        'tag_prefix' => 'OG1',
    ]);

    $this->manager = User::factory()->create([
        'restaurant_id' => $this->restaurant->id,
    ]);
    $this->manager->assignRole('manager');

    $this->waiter = User::factory()->create([
        'restaurant_id' => null,
        'global_waiter_number' => 'TIPTAP-W-00002',
        'waiter_code' => null,
    ]);
    $this->waiter->assignRole('waiter');
});

test('manager can search unlinked waiter by global number', function () {
    $response = $this->actingAs($this->manager)->getJson(route('manager.waiters.search', [
        'q' => 'TIPTAP-W-00002',
    ]));

    $response->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonPath('waiter.global_waiter_number', 'TIPTAP-W-00002')
        ->assertJsonPath('waiter.is_linked', false)
        ->assertJsonPath('waiter.is_linked_to_my_restaurant', false);
});

test('manager search marks waiter linked to own restaurant', function () {
    $this->waiter->update([
        'restaurant_id' => $this->restaurant->id,
        'waiter_code' => 'UR1-W01',
    ]);

    $response = $this->actingAs($this->manager)->getJson(route('manager.waiters.search', [
        'q' => 'TIPTAP-W-00002',
    ]));

    $response->assertSuccessful()
        ->assertJsonPath('waiter.is_linked', true)
        ->assertJsonPath('waiter.is_linked_to_my_restaurant', true);
});

test('manager can link unlinked waiter to restaurant', function () {
    $response = $this->actingAs($this->manager)->post(route('manager.waiters.link', $this->waiter), [
        'employment_type' => 'permanent',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->waiter->refresh();

    expect($this->waiter->restaurant_id)->toBe($this->restaurant->id)
        ->and($this->waiter->waiter_code)->toStartWith('UR1-W');
});

test('manager cannot link waiter already linked elsewhere', function () {
    $this->waiter->update([
        'restaurant_id' => $this->otherRestaurant->id,
        'waiter_code' => 'OG1-W01',
    ]);

    $response = $this->actingAs($this->manager)->post(route('manager.waiters.link', $this->waiter), [
        'employment_type' => 'permanent',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('manager api search and link work with sanctum token', function () {
    Sanctum::actingAs($this->manager);

    $search = $this->getJson('/api/v1/manager/waiters/search?q=TIPTAP-W-00002');
    $search->assertSuccessful()
        ->assertJsonPath('data.is_linked', false);

    $link = $this->postJson('/api/v1/manager/waiters/'.$this->waiter->id.'/link', [
        'employment_type' => 'permanent',
    ]);

    $link->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.waiter_code', fn ($code) => str_starts_with($code, 'UR1-W'));
});
