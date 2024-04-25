<?php

namespace App\Http\Controllers;

use App\Http\Resources\FunctionalAreaResource;
use App\Models\FunctionalArea;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FunctionalAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        return FunctionalAreaResource::collection(FunctionalArea::all());
    }
}
