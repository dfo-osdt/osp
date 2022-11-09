<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagementReviewStepResource;
use App\Models\ManagementReviewStep;
use App\Queries\ManagementReviewStepListQuery;
use App\Traits\PaginationLimitTrait;
use Auth;
use Illuminate\Http\Request;

class UserManagementReviewStepsController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Return all management review steps associated with the logged int user.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $limit = $this->getLimitFromRequest($request);
        $baseQuery = ManagementReviewStep::where('user_id', Auth::user()->id)->with('manuscriptRecord.user', 'previousStep.user');
        $listQuery = new ManagementReviewStepListQuery($request, $baseQuery);

        return ManagementReviewStepResource::collection($listQuery->paginate($limit)->appends($request->query()));
    }
}
