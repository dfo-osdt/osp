<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthenticatedUserResource;
use App\Http\Resources\InvitationResource;
use App\Http\Resources\UserAuthenticationResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticatedUserController extends Controller
{
    public function user(Request $request): JsonResource
    {
        $user = User::find($request->user()->id)->load('author.organization');
        return AuthenticatedUserResource::make($user);
    }

    public function authentications(Request $request)
    {
        // get the last authentications (reverse order by id)
        $authentications = $request->user()->authentications()->orderByDesc('id')->limit(50)->get();

        return UserAuthenticationResource::collection($authentications);
    }

    public function invitations(Request $request): JsonResource
    {
        // get this user's invitations (reverse order by id)
        $invitations = $request->user()->sentInvitations()->orderByDesc('id')->limit(50)->get();

        return InvitationResource::collection($invitations);
    }
}
