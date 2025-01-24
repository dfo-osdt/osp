<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\InvitedUserController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Azure\AzureOAuthController;
use Illuminate\Support\Facades\Route;

/**
 * These routes are only available if the Azure OAuth integration is disabled
 * They could be used at the same time but this would likely cause confusion
 * so it is recommended to only use one or the other.
 */
if (! config('osp.azure.enable_auth')) {

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware(['guest', 'throttle:6,1', 'respond.json'])
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(['guest', 'respond.json'])
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware(['guest', 'respond.json'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest', 'respond.json')
        ->name('password.update');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1', 'respond.json'])
        ->name('verification.send');

    Route::post('/change-password', [ChangePasswordController::class, '__invoke'])
        ->middleware(['auth', 'respond.json', 'locale'])
        ->name('change.password');

    Route::get('/verify-invitation/{id}/{hash}', [InvitedUserController::class, 'accept'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('invitation.verify');
}

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware(['auth', 'respond.json'])
    ->name('logout');

// Azure OAuth integration routes
if (config('osp.azure.enable_auth')) {
    Route::get('/oauth/azure/redirect', [AzureOAuthController::class, 'redirect']);
    Route::get('/oauth/azure/callback', [AzureOAuthController::class, 'callback']);
}
