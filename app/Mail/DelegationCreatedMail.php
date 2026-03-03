<?php

namespace App\Mail;

use App\Models\ManagementReviewDelegation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DelegationCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ManagementReviewDelegation $delegation) {}

    public function build()
    {
        $mailbox = config('osp.manuscript_submission_email');

        $this->subject('Management Review Delegation Created / Délégation de révision de gestion créée');
        $this->to($mailbox);
        $this->cc([
            $this->delegation->user->email,
            $this->delegation->delegate->email,
        ]);

        return $this->markdown('mail.delegation-created', [
            'delegation' => $this->delegation,
            'owner' => $this->delegation->user,
            'delegate' => $this->delegation->delegate,
        ]);
    }
}
