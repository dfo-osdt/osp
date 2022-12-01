<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManuscriptRecordResource;
use App\Queries\ManuscriptRecordQuery;
use App\Traits\PaginationLimitTrait;
use Auth;
use Illuminate\Http\Request;

class UserManuscriptRecordController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of manuscripts a user can see.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $this->getLimitFromRequest(request());

        $baseQuery = new ManuscriptRecordQuery($request);

        $listQuery = $baseQuery->where('user_id', Auth::user()->id)
            ->orWhereHas('manuscriptAuthors', function ($q) {
                $q->whereHas('author', function ($q) {
                    $q->where('user_id', Auth::user()->id);
                });
            })
            ->orWhereHas('managementReviewSteps', function ($q) {
                $q->where('user_id', Auth::user()->id);
            })->with('manuscriptAuthors.organization', 'manuscriptAuthors.author');

        return ManuscriptRecordResource::collection($listQuery->paginate($limit)->appends($request->query()));
    }
}
