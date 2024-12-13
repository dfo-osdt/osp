<?php

namespace App\Http\Controllers;

use App\Http\Resources\JournalResource;
use App\Models\Journal;
use App\Queries\JournalListQuery;
use App\Traits\PaginationLimitTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        $limit = $this->getLimitFromRequest($request);
        $journalListQuery = new JournalListQuery();

        return JournalResource::collection($journalListQuery->paginate($limit));
    }

    /**
     * Display the specified resource.
     */
    public function show(Journal $journal): JsonResource
    {
        return new JournalResource($journal);
    }
}
