<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthenticatedUserResource;
use App\Http\Resources\UserAuthenticationResource;
use Illuminate\Http\Request;

class AuthenticatedUserController extends Controller
{
    public function user(Request $request)
    {
        return AuthenticatedUserResource::make($request->user()->load('author.organization'));
    }

    public function authentications(Request $request)
    {
        // get the last authentications (reverse order by id)
        $authentications = $request->user()->authentications()->orderByDesc('id')->limit(50)->get();

        return UserAuthenticationResource::collection($authentications);
    }
}
