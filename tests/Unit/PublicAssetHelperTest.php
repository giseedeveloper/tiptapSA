<?php

uses(Tests\TestCase::class);

it('returns relative path when app url is localhost', function () {
    config(['app.url' => 'http://localhost', 'app.asset_url' => null]);

    expect(public_asset('images/logo.png'))->toBe('/images/logo.png');
});

it('returns full url when app url is production domain', function () {
    config(['app.url' => 'https://tiptap.example.com', 'app.asset_url' => null]);

    expect(public_asset('favicon.ico'))->toBe('https://tiptap.example.com/favicon.ico');
});

it('prefers asset url when set', function () {
    config([
        'app.url' => 'https://tiptap.example.com',
        'app.asset_url' => 'https://cdn.example.com/app',
    ]);

    expect(public_asset('images/flags/za.svg'))->toBe('https://cdn.example.com/app/images/flags/za.svg');
});
