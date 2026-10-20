<?php

namespace App\Http\Controllers;

use App\Http\Resources\SectorResource;
use App\Models\Sector;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        return SectorResource::collection(Sector::query()->orderBy('name_en')->get());
    }
}
