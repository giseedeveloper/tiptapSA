<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('serve-storage returns menu image from public disk', function () {
    Storage::fake('public');

    Storage::disk('public')->put('menu_images/test-menu.jpg', 'fake-image-bytes');

    $response = $this->get(route('storage.serve', ['path' => 'menu_images/test-menu.jpg']));

    $response->assertSuccessful();
    $response->assertHeader('content-disposition');
});

test('serve-storage rejects paths outside allowed folders', function () {
    Storage::fake('public');

    Storage::disk('public')->put('secret.txt', 'nope');

    $response = $this->get(route('storage.serve', ['path' => 'secret.txt']));

    $response->assertNotFound();
});
