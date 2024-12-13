<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Queries\UserListQuery;
use App\Traits\PaginationLimitTrait;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $limit = $this->getLimitFromRequest($request);
        $userListQuery = new UserListQuery;

        return UserResource::collection($userListQuery->paginate($limit)->appends($request->query()));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResource
    {
        Gate::authorize('view', $user);

        $user->load('author');

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function update(Request $request, User $user): JsonResource
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
}
