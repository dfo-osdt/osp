
<?php

use App\Models\User;

test('the index can be loaded successfully', function (): void {
    $page = visit('/');
    $page->wait(1);

    $page->assertSee('EOS Open Science Portal');

    assertNoRealJavascriptErrors($page);
    $page->screenshot(fullPage: true, filename: 'index-page');

});

test('a user can see the login page', function (): void {
    $page = visit('/');
    $page->click('Login');

    $page->assertSee('Email');

    $page->screenshot(fullPage: true, filename: 'login-page');
});

test('an authenticated user can see the dashboard', function (): void {

    $user = User::factory()->create();
    $this->actingAs($user);

    $page = visit('/');
    $page->wait(1);
    $page->click('[data-test="user-menu-button"]');
    $page->click('[data-test="dashboard-item"]');
    $page->wait(1);

    assertNoRealJavascriptErrors($page);
    $page->assertSee('Recent');
    $page->screenshot(fullPage: true, filename: 'dashboard-page');

});
