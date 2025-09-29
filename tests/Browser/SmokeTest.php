
<?php

use App\Models\User;

test('the index can be loaded successfully', function (): void {
    $page = visit('/');
    $page->wait(1);

    $page->assertSee('EOS Open Science Portal');

    $page->assertNoJavascriptErrors();
    $page->screenshot(filename: 'index-page', fullPage: true);

});

test('a user can see the login page', function (): void {
    $page = visit('/');
    $page->click('Login');

    $page->assertSee('Email');

    $page->screenshot(filename: 'login-page', fullPage: true);
});

test('an authenticated user can see the dashboard', function (): void {

    $user = User::factory()->create();
    $this->actingAs($user);

    $page = visit('/');
    $page->wait(1);
    $page->click('[data-test="user-menu-button"]');
    $page->click('[data-test="dashboard-item"]');
    $page->wait(1);

    $page->assertNoJavascriptErrors();
    $page->assertSee('Recent');
    $page->screenshot(filename: 'dashboard-page', fullPage: true);

});
