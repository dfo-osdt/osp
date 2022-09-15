<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManuscriptRecordResource;
use App\Models\ManuscriptRecord;

class UserManuscriptRecordController extends Controller
{
    /**
     * Display a listing of manuscripts a user can see.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return a list of all manuscripts a user can see
        $manuscripts = ManuscriptRecord::where('user_id', auth()->id())->with('manuscriptAuthors.organization')->get();

        return ManuscriptRecordResource::collection($manuscripts);
    }
}
