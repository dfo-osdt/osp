<?php

use App\Models\Region;
use App\Models\Sector;
use App\Models\User;

test('a DFO author with an incomplete profile is reminded and can open their author profile', function (): void {
    $this->actingAs(User::factory()->create());

    $page = visit('/');

    $page->waitForText('Please complete your author profile')
        ->assertPresent('[data-test="profile-update-dot"]')
        ->assertPresent('.q-drawer [aria-label="Author Profile"] .q-badge')
        ->click('Go to profile')
        ->waitForText('DFO Sector')
        ->assertSee('DFO Region');

    assertNoRealJavascriptErrors($page);
});

test('a DFO author with a complete profile is not reminded', function (): void {
    $user = User::factory()->create();
    $user->author->update(['sector_id' => Sector::factory()->create()->id, 'region_id' => Region::query()->value('id')]);
    $this->actingAs($user);

    $page = visit('/');

    // opening the user menu proves the authenticated user has loaded
    $page->click('[data-test="user-menu-button"]')
        ->waitForText('Dashboard')
        ->assertDontSee('Please complete your author profile')
        ->assertMissing('[data-test="profile-update-dot"]')
        ->assertMissing('.q-drawer [aria-label="Author Profile"] .q-badge');

    assertNoRealJavascriptErrors($page);
});
