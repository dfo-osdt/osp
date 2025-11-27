<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManuscriptRecordSummaryResource;
use App\Queries\MyManuscriptsRecordQuery;
use App\Traits\PaginationLimitTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserManuscriptRecordController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of manuscripts a user can see.
     */
    public function index(Request $request): ResourceCollection
    {
        $limit = $this->getLimitFromRequest(request());

        $userId = Auth::id();

        /**
         * Initially, this query was part of the base query, however,
         * this caused the filter to be ignored. Since is very unlikely
         * to have thousands of records, this should not be a problem.
         * If it is, we can always review this query.
         */
        $manuscriptIds = \App\Models\ManuscriptRecord::query()->select('id')
            ->where('user_id', $userId)
            ->orWhereHas('manuscriptAuthors', function (\Illuminate\Contracts\Database\Query\Builder $q) use ($userId): void {
                $q->whereHas('author', function (\Illuminate\Contracts\Database\Query\Builder $q) use ($userId): void {
                    $q->where('user_id', $userId);
                });
            })
            ->orWhereHas('sharedWithUsers', function ($q) use ($userId): void {
                $q->where('user_id', $userId);
            });

        if ($request->get('include-reviews') === 'true') {
            $manuscriptIds->orWhereHas('managementReviewSteps', function ($q) use ($userId): void {
                $q->where('user_id', $userId);
            });
        }
        $manuscriptIds = $manuscriptIds->pluck('id');

        $relationship = [
            'manuscriptAuthors' => [
                'author',
                'organization',
            ],
            'shareables',
            'managementReviewSteps',
            'region',
        ];

        $baseQuery = \App\Models\ManuscriptRecord::query()->whereIn('id', $manuscriptIds)
            ->with($relationship);

        $listQuery = new MyManuscriptsRecordQuery($request, $baseQuery);

        return ManuscriptRecordSummaryResource::collection($listQuery->paginate($limit)->appends($request->query()));
    }
}
