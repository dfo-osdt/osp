<?php

namespace App\Http\Controllers;

use App\Http\Resources\PreprintResource;
use App\Queries\PreprintListQuery;
use App\Traits\PaginationLimitTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PreprintController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $limit = $this->getLimitFromRequest($request);
        $preprintQuery = new PreprintListQuery;

        return PreprintResource::collection($preprintQuery->paginate($limit));
    }
}
