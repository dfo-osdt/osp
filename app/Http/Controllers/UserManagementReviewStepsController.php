<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManagementReviewStepResource;
use App\Queries\ManagementReviewStepListQuery;
use App\Traits\PaginationLimitTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserManagementReviewStepsController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Return all management review steps associated with the logged int user.
     */
    public function index(Request $request): ResourceCollection
    {
        $userId = Auth::id();

        $limit = $this->getLimitFromRequest($request);
        $baseQuery = \App\Models\ManagementReviewStep::query()->where('user_id', $userId)->with('manuscriptRecord.user', 'previousStep.user');
        $listQuery = new ManagementReviewStepListQuery($request, $baseQuery);

        return ManagementReviewStepResource::collection($listQuery->paginate($limit)->appends($request->query()));
    }
}
