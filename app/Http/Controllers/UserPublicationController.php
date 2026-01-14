<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicationResource;
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
        $publicationIds = \App\Models\Publication::query()->select('id')
            ->where('user_id', $userId)
            ->orWhereHas('publicationAuthors', function (\Illuminate\Contracts\Database\Query\Builder $q) use ($userId): void {
                $q->whereHas('author', function (\Illuminate\Contracts\Database\Query\Builder $q) use ($userId): void {
                    $q->where('user_id', $userId);
                });
            })
            ->pluck('id');

        $baseQuery = \App\Models\Publication::query()->whereIn('id', $publicationIds)
            ->with([
                'manuscriptRecord',
                'journal',
                'publicationAuthors' => fn ($q) => $q->with('author', 'organization')->chaperone('publication'),
                'region',
            ]);

        $listQuery = new PublicationListQuery($request, $baseQuery);

        return PublicationResource::collection($listQuery->paginate($limit)->appends($request->query()));
    }
}
