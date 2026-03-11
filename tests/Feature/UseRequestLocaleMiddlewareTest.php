<?php

test('locale defaults to en when no Accept-Language header is sent', function (): void {
    $this->getJson('/api/stats')
        ->assertSuccessful();

    expect(app()->getLocale())->toBe('en');
});

test('locale is set to fr when Accept-Language header is fr', function (): void {
    $this->withHeaders(['Accept-Language' => 'fr'])
        ->getJson('/api/stats')
        ->assertSuccessful();

    expect(app()->getLocale())->toBe('fr');
});

test('locale is set to en when Accept-Language header is en', function (): void {
    $this->withHeaders(['Accept-Language' => 'en'])
        ->getJson('/api/stats')
        ->assertSuccessful();

    expect(app()->getLocale())->toBe('en');
});

test('locale falls back to default when Accept-Language header is unsupported', function (): void {
    $this->withHeaders(['Accept-Language' => 'de'])
        ->getJson('/api/stats')
        ->assertSuccessful();

    expect(app()->getLocale())->toBe('en');
});

test('locale falls back to request parameter when no valid header is present', function (): void {
    $this->getJson('/api/stats?locale=fr')
        ->assertSuccessful();

    expect(app()->getLocale())->toBe('fr');
});

test('Accept-Language header takes priority over locale request parameter', function (): void {
    $this->withHeaders(['Accept-Language' => 'en'])
        ->getJson('/api/stats?locale=fr')
        ->assertSuccessful();

    expect(app()->getLocale())->toBe('en');
});

test('invalid locale in request parameter falls back to default', function (): void {
    $this->getJson('/api/stats?locale=de')
        ->assertSuccessful();

    expect(app()->getLocale())->toBe('en');
});
