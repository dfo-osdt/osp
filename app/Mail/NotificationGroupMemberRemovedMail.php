<?php

namespace App\Mail;

use App\Models\NotificationGroupMember;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationGroupMemberRemovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public NotificationGroupMember $notificationGroupMember) {}

    public function build()
    {
        $owner = $this->notificationGroupMember->user;
        $member = $this->notificationGroupMember->member;

        $this->subject('Notification Group Update / Mise à jour du groupe de notification');
        $this->to($owner->email, $owner->fullName);

        return $this->markdown('mail.notification-group-member-removed', [
            'owner' => $owner,
            'member' => $member,
        ]);
    }
}
