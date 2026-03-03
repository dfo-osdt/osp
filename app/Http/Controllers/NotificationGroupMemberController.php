<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationGroupMemberRequest;
use App\Http\Resources\NotificationGroupMemberResource;
use App\Models\NotificationGroupMember;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class NotificationGroupMemberController extends Controller
{
    public function index(): JsonResource
    {
        $members = Auth::user()
            ->notificationGroupMembers()
            ->with('member')
            ->orderByDesc('created_at')
            ->get();

        return NotificationGroupMemberResource::collection($members);
    }

    public function store(StoreNotificationGroupMemberRequest $request): JsonResource
    {
        $member = Auth::user()->notificationGroupMembers()->updateOrCreate(
            ['member_user_id' => $request->validated('member_user_id')],
            $request->validated(),
        );
        $member->load('member');

        return new NotificationGroupMemberResource($member);
    }

    public function destroy(NotificationGroupMember $notificationGroupMember): JsonResource
    {
        if ($notificationGroupMember->user_id !== Auth::id()) {
            abort(403);
        }

        $notificationGroupMember->delete();

        return new NotificationGroupMemberResource($notificationGroupMember);
    }
}
