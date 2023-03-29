<?php

use App\Http\Controllers\Auth\InvitedUserController;
use App\Http\Controllers\AuthenticatedUserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\FunderController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ManagementReviewStepController;
use App\Http\Controllers\ManuscriptAuthorController;
use App\Http\Controllers\ManuscriptRecordController;
use App\Http\Controllers\ManuscriptRecordFundingSourceController;
use App\Http\Controllers\OpenAI\GeneratePLSController;
use App\Http\Controllers\Orcid\ImplicitFlowController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PublicationAuthorController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicationFundingSourceController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementReviewStepsController;
use App\Http\Controllers\UserManuscriptRecordController;
use App\Http\Controllers\UserPublicationController;
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

// Need to ben authenticated to access these routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(AuthenticatedUserController::class)->group(function () {
        Route::get('/user', 'user');
        Route::get('/user/authentications', 'authentications');
        Route::get('/user/invitations', 'invitations');
    });

    // ORCID routes
    Route::post('/user/orcid/verify', ImplicitFlowController::class);
    Route::get('/user/orcid/verify', [ImplicitFlowController::class, 'redirect']);

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

    Route::controller(InvitedUserController::class)->group(function () {
        Route::post('/users/invite', 'invite');
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
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/reassign', 'reassign');
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
        Route::put('manuscript-records/{manuscriptRecord}/withdraw', 'withdraw');
        Route::put('manuscript-records/{manuscriptRecord}/submitted', 'submitted');
        Route::put('manuscript-records/{manuscriptRecord}/accepted', 'accepted');
    });

    Route::controller(ManuscriptRecordFundingSourceController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}/funding-sources', 'index');
        Route::post('/manuscript-records/{manuscriptRecord}/funding-sources', 'store');
        Route::put('/manuscript-records/{manuscriptRecord}/funding-sources/{fundingSource}', 'update');
        Route::delete('/manuscript-records/{manuscriptRecord}/funding-sources/{fundingSource}', 'destroy');
    });

    Route::controller(PublicationFundingSourceController::class)->group(function () {
        Route::get('/publications/{publication}/funding-sources', 'index');
        Route::post('/publications/{publication}/funding-sources', 'store');
        Route::put('/publications/{publication}/funding-sources/{fundingSource}', 'update');
        Route::delete('/publications/{publication}/funding-sources/{fundingSource}', 'destroy');
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

    Route::controller(FunderController::class)->group(function () {
        Route::get('/funders', 'index');
        Route::get('/funders/{funder}', 'show');
    });

    Route::controller(PublicationController::class)->group(function () {
        Route::get('/publications', 'index');
        Route::get('/publications/{publication}', 'show');
        Route::put('/publications/{publication}', 'update');
        Route::post('/publications', 'store');
        Route::post('/publications/{publication}/pdf', 'attachPdf');
        Route::get('/publications/{publication}/pdf', 'getPDFInfo');
        Route::get('/publications/{publication}/pdf/download', 'downloadPdf');
    });

    Route::controller(PublicationAuthorController::class)->group(function () {
        Route::get('/publications/{publication}/publication-authors', 'index');
        Route::get('/publications/{publication}/publication-authors/{publicationAuthor}', 'show');
        Route::post('/publications/{publication}/publication-authors', 'store');
        Route::put('/publications/{publication}/publication-authors/{publicationAuthor}', 'update');
        Route::delete('/publications/{publication}/publication-authors/{publicationAuthor}', 'destroy');
    });

    Route::get('/regions', [RegionController::class, 'index']);

    Route::prefix('utils')->group(function () {
        Route::post('/generate-pls', GeneratePLSController::class);
    });
});
