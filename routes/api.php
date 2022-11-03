<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ManagementReviewStepController;
use App\Http\Controllers\ManuscriptAuthorController;
use App\Http\Controllers\ManuscriptRecordController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementReviewStepsController;
use App\Http\Controllers\UserManuscriptRecordController;
use App\Http\Controllers\UserPublicationController;
use App\Http\Resources\AuthenticatedUserResource;
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
        return AuthenticatedUserResource::make($request->user()->load('author.organization'));
    });

    Route::controller(AuthorController::class)->group(function () {
        Route::get('/authors', 'index');
        Route::get('/authors/{author}', 'show');
        Route::post('/authors', 'store');
        Route::put('/authors/{author}', 'update');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index');
        Route::get('/users/{user}', 'show');
        Route::put('/users/{user}', 'update');
    });

    Route::controller(ManuscriptAuthorController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}/manuscript-authors', 'index');
        Route::get('/manuscript-records/{manuscriptRecord}/manuscript-authors/{manuscriptAuthor}', 'show');
        Route::put('/manuscript-records/{manuscriptRecord}/manuscript-authors/{manuscriptAuthor}', 'update');
        Route::delete('/manuscript-records/{manuscriptRecord}/manuscript-authors/{manuscriptAuthor}', 'destroy');
        Route::post('/manuscript-records/{manuscriptRecord}/manuscript-authors', 'store');
    });

    Route::controller(ManagementReviewStepController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}/management-review-steps', 'index');
        Route::post('/manuscript-records/{manuscriptRecord}/management-review-steps', 'store');
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}', 'update');
        // actions
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/approve', 'approve');
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/withhold', 'withhold');
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/defer', 'defer');
    });

    Route::controller(ManuscriptRecordController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}', 'show');
        Route::put('manuscript-records/{manuscriptRecord}', 'update');
        Route::post('/manuscript-records', 'store');
        // upload/download attached manuscript pdf
        Route::get('manuscript-records/{manuscriptRecord}/pdf', 'downloadPdf');
        Route::post('manuscript-records/{manuscriptRecord}/pdf', 'attachPdf');
        // actions
        Route::put('manuscript-records/{manuscriptRecord}/submit-for-review', 'submitForReview');
    });

    // routes for user specific resources
    Route::prefix('my')->group(function () {
        Route::get('/manuscript-records', [UserManuscriptRecordController::class, 'index']);
        Route::get('/management-review-steps', [UserManagementReviewStepsController::class, 'index']);
        Route::get('/publications/', [UserPublicationController::class, 'index']);
    });

    Route::controller(OrganizationController::class)->group(function () {
        Route::get('/organizations', 'index');
        Route::get('/organizations/{organization}', 'show');
        Route::put('organizations/{organization}', 'update');
        Route::post('/organizations', 'store');
    });

    Route::controller(JournalController::class)->group(function () {
        Route::get('/journals', 'index');
        Route::get('/journals/{journal}', 'show');
    });

    Route::controller(PublicationController::class)->group(function () {
        Route::get('/publications', 'index');
        Route::get('/publications/{publication}', 'show');
    });

    Route::get('/regions', [RegionController::class, 'index']);
});
