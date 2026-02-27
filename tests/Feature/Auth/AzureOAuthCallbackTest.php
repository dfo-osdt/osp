<?php

use App\Http\Controllers\Azure\AzureOAuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

it('redirects to login with error when azure oauth state is invalid', function (): void {
    Route::get('/oauth/azure/callback', [AzureOAuthController::class, 'callback']);

    Socialite::shouldReceive('driver')
        ->with('azure')
        ->andReturnSelf()
        ->shouldReceive('user')
        ->andThrow(new InvalidStateException);

    $response = $this->get('/oauth/azure/callback');

    $response->assertRedirect();
    expect($response->headers->get('Location'))
        ->toContain('error=oauth_error')
        ->toContain('error_description=');
});
