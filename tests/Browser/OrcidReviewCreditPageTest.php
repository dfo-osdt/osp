<?php

use App\Models\User;

test('an author without ORCID write access can open the review credit page and sees the connect banner', function (): void {
    $this->actingAs(User::factory()->create());

    $page = visit('/');

    $page->click('[data-test="user-menu-button"]')
        ->click('[data-test="settings-item"]')
        ->click('Author Profile')
        ->click('[data-test="orcid-review-credit-link"]')
        ->waitForText('None of your review contributions can be added to ORCID yet')
        ->assertPresent('[data-test="orcid-connect-banner"]');

    assertNoRealJavascriptErrors($page);
});
