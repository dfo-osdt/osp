<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Queries\UserListQuery;
use App\Traits\PaginationLimitTrait;
use Gate;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $this->getLimitFromRequest($request);
        $userListQuery = new UserListQuery($request);

        return UserResource::collection($userListQuery->paginate($limit)->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        $user->load('author');

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'locale' => 'string|in:en,fr',
        ]);

        $user->update($validated);
        // also update the author record associated with this user
        $authorVariables = collect($validated)->only(['first_name', 'last_name'])->toArray();
        $user->author->update($authorVariables);
        $user->refresh();

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
