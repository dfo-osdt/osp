
<?php

test('the index can be loaded successfully', function (): void {
    $page = visit('/');

    $page->assertSee('EOS Open Science Portal');

    $page->screenshot(filename: 'index-page', fullPage: true);
});

test('a user can see the login page', function (): void {
    $page = visit('/');
    $page->click('Login');

    $page->assertSee('Email');

    $page->screenshot(filename: 'login-page', fullPage: true);
});
