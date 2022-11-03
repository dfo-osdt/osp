<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicationResource;
use App\Models\Publication;
use App\Queries\PublicationListQuery;
use App\Traits\PaginationLimitTrait;
use Auth;
use Illuminate\Http\Request;

class UserPublicationController extends Controller
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
        $baseQuery = Publication::where('user_id', Auth::user()->id)->with('manuscriptRecord');
        $listQuery = new PublicationListQuery($request, $baseQuery);

        return PublicationResource::collection($listQuery->paginate($limit)->appends($request->query()));
    }
}
