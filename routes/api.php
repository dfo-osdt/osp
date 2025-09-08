<?php

use App\Http\Controllers\Announcement\CheckAnnouncementController;
use App\Http\Controllers\Auth\InvitedUserController;
use App\Http\Controllers\AuthenticatedUserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AuthorEmploymentController;
use App\Http\Controllers\AuthorExpertiseController;
use App\Http\Controllers\AuthorPublicationController;
use App\Http\Controllers\Azure\AzureDirectorySearchController;
use App\Http\Controllers\ExpertiseController;
use App\Http\Controllers\FunctionalAreaController;
use App\Http\Controllers\FunderController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ManagementReviewStepController;
use App\Http\Controllers\ManuscriptAuthorController;
use App\Http\Controllers\ManuscriptPeerReviewerController;
use App\Http\Controllers\ManuscriptRecordController;
use App\Http\Controllers\ManuscriptRecordFileController;
use App\Http\Controllers\ManuscriptRecordFundingSourceController;
use App\Http\Controllers\ManuscriptRecordSharingController;
use App\Http\Controllers\OpenAI\GeneratePLSController;
use App\Http\Controllers\Orcid\FullFlowController;
use App\Http\Controllers\Orcid\RevokeTokenController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PreprintController;
use App\Http\Controllers\PublicationAuthorController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicationFileController;
use App\Http\Controllers\PublicationFundingSourceController;
use App\Http\Controllers\PublicationSupplementaryFileController;
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

Route::get('/announcements', CheckAnnouncementController::class);
Route::get('/orcid/callback', [FullFlowController::class, 'callback']);

// Need to be authenticated to access these routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(AuthenticatedUserController::class)->group(function () {
        Route::get('/user', 'user');
        Route::get('/user/authentications', 'authentications');
        Route::get('/user/invitations', 'invitations');
    });

    // Routes for 3-legged OAuth flow
    Route::get('/user/orcid/verify', [FullFlowController::class, 'redirect']);
    Route::post('/user/orcid/revoke', RevokeTokenController::class);

    // Look for AAD users to invite
    Route::post('/azure-directory/users', [AzureDirectorySearchController::class, '__invoke']);

    Route::controller(AuthorController::class)->group(function () {
        Route::get('/authors', 'index');
        Route::get('/authors/{author}', 'show');
        Route::post('/authors', 'store');
        Route::put('/authors/{author}', 'update');
    });

    Route::controller(AuthorEmploymentController::class)->group(function () {
        Route::get('/authors/{author}/employments', 'index');
        Route::post('/authors/{author}/employments', 'store');
        Route::get('/authors/{author}/employments/{authorEmployment}', 'show');
        Route::put('/authors/{author}/employments/{authorEmployment}', 'update');
        Route::delete('/authors/{author}/employments/{authorEmployment}', 'destroy');
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

    Route::controller(ManuscriptPeerReviewerController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}/peer-reviewers', 'index');
        Route::post('/manuscript-records/{manuscriptRecord}/peer-reviewers', 'store');
        Route::get('/manuscript-records/{manuscriptRecord}/peer-reviewers/{manuscriptPeerReviewer}', 'show');
        Route::delete('/manuscript-records/{manuscriptRecord}/peer-reviewers/{manuscriptPeerReviewer}', 'destroy');
    });

    Route::controller(ManagementReviewStepController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}/management-review-steps', 'index');
        Route::post('/manuscript-records/{manuscriptRecord}/management-review-steps', 'store');
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}', 'update');
        // actions
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/complete', 'complete');
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/refer', 'refer');
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/reassign', 'reassign');
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/revision', 'revision');
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/revision-response', 'revisionResponse');
        Route::put('/manuscript-records/{manuscriptRecord}/management-review-steps/{managementReviewStep}/withdraw', 'withdraw');
    });

    Route::controller(ManuscriptRecordController::class)->group(function () {
        Route::get('/manuscript-records', 'index');
        Route::get('/manuscript-records/{manuscriptRecord}', 'show');
        Route::get('/manuscript-records/{manuscriptRecord}/metadata', 'metadata');
        Route::put('manuscript-records/{manuscriptRecord}', 'update');
        Route::delete('manuscript-records/{manuscriptRecord}', 'destroy');
        Route::post('/manuscript-records', 'store');
        // actions
        Route::put('manuscript-records/{manuscriptRecord}/submit-for-review', 'submitForReview');
        Route::put('manuscript-records/{manuscriptRecord}/withdraw', 'withdraw');
        Route::put('manuscript-records/{manuscriptRecord}/submitted', 'submitted');
        Route::put('manuscript-records/{manuscriptRecord}/submitted-to-preprint', 'submittedToPreprint');
        Route::put('manuscript-records/{manuscriptRecord}/accepted', 'accepted');
        Route::post('manuscript-records/{manuscriptRecord}/clone', 'clone');
    });

    Route::controller(PreprintController::class)->group(function () {
        Route::get('/preprints', 'index');
    });

    Route::controller(ManuscriptRecordSharingController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}/sharing', 'index');
        Route::post('/manuscript-records/{manuscriptRecord}/sharing', 'store');
        Route::get('/manuscript-records/{manuscriptRecord}/sharing/{shareable}', 'show');
        Route::put('/manuscript-records/{manuscriptRecord}/sharing/{shareable}', 'update');
        Route::delete('/manuscript-records/{manuscriptRecord}/sharing/{shareable}', 'destroy');
    });

    Route::controller(ManuscriptRecordFileController::class)->group(function () {
        Route::get('/manuscript-records/{manuscriptRecord}/files', 'index');
        Route::post('/manuscript-records/{manuscriptRecord}/files', 'store');
        Route::get('/manuscript-records/{manuscriptRecord}/files/{uuid}', 'show');
        Route::delete('/manuscript-records/{manuscriptRecord}/files/{uuid}', 'destroy');
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

    Route::controller(ExpertiseController::class)->group(function () {
        Route::get('/expertises', 'index');
        Route::get('/expertises/{expertise}', 'show');
    });

    Route::controller(AuthorExpertiseController::class)->group(function () {
        Route::get('/authors/{author}/expertises', 'index');
        Route::post('/authors/{author}/expertises', 'store');
    });

    Route::controller(AuthorPublicationController::class)->group(function () {
        Route::get('/authors/{author}/publications', 'index');
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
    });

    Route::controller(PublicationFileController::class)->group(function () {
        Route::get('/publications/{publication}/files', 'index');
        Route::post('/publications/{publication}/files', 'store');
        Route::get('/publications/{publication}/files/{uuid}', 'show');
        Route::delete('/publications/{publication}/files/{uuid}', 'destroy');
    });

    Route::controller(PublicationSupplementaryFileController::class)->group(function () {
        Route::get('/publications/{publication}/supplementary-files', 'index');
        Route::post('/publications/{publication}/supplementary-files', 'store');
        Route::get('/publications/{publication}/supplementary-files/{uuid}', 'show');
        Route::delete('/publications/{publication}/supplementary-files/{uuid}', 'destroy');
    });

    Route::controller(PublicationAuthorController::class)->group(function () {
        Route::get('/publications/{publication}/publication-authors', 'index');
        Route::get('/publications/{publication}/publication-authors/{publicationAuthor}', 'show');
        Route::post('/publications/{publication}/publication-authors', 'store');
        Route::put('/publications/{publication}/publication-authors/{publicationAuthor}', 'update');
        Route::delete('/publications/{publication}/publication-authors/{publicationAuthor}', 'destroy');
    });

    Route::get('/regions', [RegionController::class, 'index']);
    Route::get('/functional-areas', [FunctionalAreaController::class, 'index']);

    Route::prefix('utils')->group(function () {
        Route::post('/generate-pls', [GeneratePLSController::class, 'generate']);
        Route::post('/translate-pls', [GeneratePLSController::class, 'translate']);
    });
});
