<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationGroupMemberResource;
use App\Mail\NotificationGroupMemberRemovedMail;
use App\Models\NotificationGroupMember;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NotificationGroupMembershipController extends Controller
{
    public function index(): JsonResource
    {
        $memberships = Auth::user()
            ->notificationGroupMemberships()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        return NotificationGroupMemberResource::collection($memberships);
    }

    public function destroy(NotificationGroupMember $notificationGroupMember): JsonResource
    {
        if ($notificationGroupMember->member_user_id !== Auth::id()) {
            abort(403);
        }

        $notificationGroupMember->load('user', 'member');

        activity()
            ->performedOn($notificationGroupMember)
            ->causedBy(Auth::user())
            ->log('self-removed from notification group');

        Mail::queue(new NotificationGroupMemberRemovedMail($notificationGroupMember));

        $notificationGroupMember->delete();

        return new NotificationGroupMemberResource($notificationGroupMember);
    }
}
