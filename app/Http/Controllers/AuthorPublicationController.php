<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\UserPermission;
use App\Enums\PublicationStatus;
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

        $baseQuery = Publication::whereHas('publicationAuthors', function ($query) use ($author) {
            $query->where('author_id', $author->id);
        })->with('journal', 'publicationAuthors.author', 'publicationAuthors.organization');

        // if user does not have permission to update all publications, only show published publications
        if (! $user->hasPermissionTo(UserPermission::UPDATE_PUBLICATIONS)) {
            $baseQuery->where('status', PublicationStatus::PUBLISHED);
        }

        $query = new PublicationListQuery($request, $baseQuery);

        return PublicationResource::collection($query->paginate($limit));
    }
}
