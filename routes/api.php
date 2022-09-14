<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ManuscriptAuthorController;
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

    Route::controller(AuthorController::class)->group(function () {
        Route::get('/authors', 'index');
        // Route::get('/authors/{author}', 'show');
        // Route::put('authors/{author}', 'update');
        Route::post('/authors', 'store');
    });

    Route::controller(ManuscriptAuthorController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}/manuscript-authors', 'index');
        Route::get('/manuscript-records/{manuscriptRecord}/manuscript-authors/{manuscriptAuthor}', 'show');
        Route::put('/manuscript-records/{manuscriptRecord}/manuscript-authors/{manuscriptAuthor}', 'update');
        Route::delete('/manuscript-records/{manuscriptRecord}/manuscript-authors/{manuscriptAuthor}', 'destroy');
        Route::post('/manuscript-records/{manuscriptRecord}/manuscript-authors', 'store');
    });

    Route::controller(ManuscriptRecordController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}', 'show');
        Route::put('manuscript-records/{manuscriptRecord}', 'update');
        Route::post('/manuscript-records', 'store');
    });

    // routes for user specific resources
    Route::prefix('my')->group(function () {
        Route::get('/manuscript-records', [UserManuscriptRecordController::class, 'index']);
    });

    Route::get('/regions', [RegionController::class, 'index']);
});
