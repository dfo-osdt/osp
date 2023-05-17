<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManuscriptRecordSummaryResource;
use App\Models\ManuscriptRecord;
use App\Queries\ManuscriptRecordQuery;
use App\Traits\PaginationLimitTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
        $manuscriptIds = ManuscriptRecord::select('id')
            ->where('user_id', $userId)
            ->orWhereHas('manuscriptAuthors', function ($q) use ($userId) {
                $q->whereHas('author', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            });

        if ($request->get('include-reviews') === 'true') {
            $manuscriptIds->orWhereHas('managementReviewSteps', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }
        $manuscriptIds = $manuscriptIds->pluck('id');

        $baseQuery = ManuscriptRecord::whereIn('id', $manuscriptIds)
            ->with('manuscriptAuthors.organization', 'manuscriptAuthors.author');

        $listQuery = new ManuscriptRecordQuery($request, $baseQuery);

        return ManuscriptRecordSummaryResource::collection($listQuery->paginate($limit)->appends($request->query()));
    }
}
