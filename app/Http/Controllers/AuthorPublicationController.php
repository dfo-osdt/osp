<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicationResource;
use App\Models\Author;
use App\Traits\PaginationLimitTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthorPublicationController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the author's publications.
     */
    public function index(Request $request, Author $author): ResourceCollection
    {
        $limit = $this->getLimitFromRequest($request);

        $publications = $author->publications()
            ->with([
                'journal',
                'publicationAuthors.author',
                'publicationAuthors.organization',
                'user',
                'region',
            ])
            ->orderBy('published_on', 'desc')
            ->paginate($limit);

        return PublicationResource::collection($publications->appends($request->query()));
    }
}
