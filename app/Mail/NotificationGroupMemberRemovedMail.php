<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationGroupMemberRemovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $owner,
        public User $member,
    ) {}

    public function build()
    {
        $this->subject('Notification Group Update / Mise à jour du groupe de notification');
        $this->to($this->owner->email, $this->owner->fullName);

        return $this->markdown('mail.notification-group-member-removed', [
            'owner' => $this->owner,
            'member' => $this->member,
        ]);
    }
}
