
<?php

use App\Models\User;

test('the index can be loaded successfully', function (): void {
    $page = visit('/');
    $page->waitForText('EOS Open Science Portal')
        ->assertSee('EOS Open Science Portal');

    assertNoRealJavascriptErrors($page);
    $page->screenshot(fullPage: true, filename: 'index-page');

});

test('a user can see the login page', function (): void {
    $page = visit('/');

    $page->waitForText('Login')
        ->click('Login')
        ->waitForText('Email')
        ->assertSee('Email');

    $page->screenshot(fullPage: true, filename: 'login-page');
});

test('an authenticated user can see the dashboard', function (): void {

    $user = User::factory()->create();
    $this->actingAs($user);

    $page = visit('/');

    $page->click('[data-test="user-menu-button"]')
        ->waitForText('Dashboard')
        ->click('[data-test="dashboard-item"]')
        ->waitForText('Recent')
        ->assertSee('Recent');

    assertNoRealJavascriptErrors($page);

    $page->screenshot(fullPage: true, filename: 'dashboard-page');

});
