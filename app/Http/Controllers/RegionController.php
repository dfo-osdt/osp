<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegionResource;
use App\Models\Region;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection<RegionResource>
     */
    public function index(): AnonymousResourceCollection
    {
        return RegionResource::collection(Region::all());
    }
}
