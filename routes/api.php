<?php

use App\Http\Controllers\ManuscriptRecordController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\UserManuscriptRecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    // need to change this...
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::controller(ManuscriptRecordController::class)->group(function () {
        Route::get('/manuscripts/{manuscriptRecord}', 'show');
        Route::post('/manuscripts', 'store');
    });

    // routes for user specific resources
    Route::prefix('my')->group(function () {
        Route::get('/manuscripts', [UserManuscriptRecordController::class, 'index']);
    });

    Route::get('/regions', [RegionController::class, 'index']);
});
