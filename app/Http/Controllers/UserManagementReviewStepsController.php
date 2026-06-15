<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\UserPermission;
use App\Http\Resources\ManagementReviewStepResource;
use App\Models\ManagementReviewStep;
use App\Queries\ManagementReviewStepListQuery;
use App\Traits\PaginationLimitTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserManagementReviewStepsController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Return all management review steps associated with the logged int user.
     */
    public function index(Request $request): ResourceCollection
    {
        $user = Auth::user();

        $limit = $this->getLimitFromRequest($request);
        $baseQuery = ManagementReviewStep::query()
            ->where('user_id', $user->id)
            ->with('manuscriptRecord.user', 'previousStep.user');
        $listQuery = new ManagementReviewStepListQuery($request, $baseQuery);

        return ManagementReviewStepResource::collection($listQuery->paginate($limit)->appends($request->query()));
    }
}
