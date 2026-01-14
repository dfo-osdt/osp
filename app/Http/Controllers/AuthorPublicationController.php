<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicationResource;
use App\Models\Author;
use App\Models\Publication;
use App\Models\User;
use App\Queries\PublicationListQuery;
use App\Traits\PaginationLimitTrait;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthorPublicationController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the author's publications.
     */
    public function index(#[CurrentUser] User $user, Request $request, Author $author): ResourceCollection
    {
        $limit = $this->getLimitFromRequest($request);

        $baseQuery = Publication::query()
            ->forUser($user)
            ->whereHas('publicationAuthors', function ($query) use ($author): void {
                $query->where('author_id', $author->id);
            })
            ->with([
                'journal',
                'publicationAuthors' => fn ($query) => $query->with('author', 'organization')->chaperone('publication'),
                'region',
            ]);

        $query = new PublicationListQuery($request, $baseQuery);

        return PublicationResource::collection($query->paginate($limit));
    }
}
