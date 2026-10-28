<?php

use App\Http\Controllers\Orcid\FullFlowController;
use Illuminate\Support\Facades\Route;
use Spatie\Csp\AddCspHeaders;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('app');
})->middleware(AddCspHeaders::class);

// Spatie Health Check
Route::get('health', HealthCheckResultsController::class);

/**
 * ORCID 3-legged OAuth flow. Both ends live in the web group so the state is always
 * bound to the browser session (the api group only starts a session for stateful referers).
 * The paths keep the api/ prefix so the registered ORCID redirect URI is unchanged.
 */
Route::get('api/orcid/callback', [FullFlowController::class, 'callback']);
Route::get('api/user/orcid/verify', [FullFlowController::class, 'redirect'])->middleware('auth');

require __DIR__.'/auth.php';
