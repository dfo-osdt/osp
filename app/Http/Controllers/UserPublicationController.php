<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicationResource;
use App\Models\Publication;
use App\Queries\PublicationListQuery;
use App\Traits\PaginationLimitTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserPublicationController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Return all management review steps associated with the logged int user.
     */
    public function index(Request $request): ResourceCollection
    {
        $limit = $this->getLimitFromRequest($request);

        $userId = Auth::id();

        /**
         * Initially, this query was part of the base query, however,
         * this caused the filter to be ignored. Since is very unlikely
         * to have thousands of records, this should not be a problem.
         * If it is, we can always review this query.
         */
        $publicationIds = Publication::select('id')
            ->where('user_id', $userId)
            ->orWhereHas('publicationAuthors', function ($q) use ($userId): void {
                $q->whereHas('author', function ($q) use ($userId): void {
                    $q->where('user_id', $userId);
                });
            })
            ->pluck('id');

        $baseQuery = Publication::whereIn('id', $publicationIds)
            ->with('manuscriptRecord',
                'journal',
                'publicationAuthors.author',
                'publicationAuthors.organization',
                'region');

        $listQuery = new PublicationListQuery($request, $baseQuery);

        return PublicationResource::collection($listQuery->paginate($limit)->appends($request->query()));
    }
}
