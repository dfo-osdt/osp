<?php

namespace App\Mail;

use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ManagementReviewPendingMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  Collection<int, ManagementReviewStep>  $reviews
     * @return void
     */
    public function __construct(public Collection $reviews, public User $user)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Weekly Summary: Pending Management Reviews / Résumé hebdomadaire: Révisions de gestion en attente';

        $this->subject($subject);
        $this->to($this->user->email, $this->user->fullName);

        return $this->markdown('mail.management-review-pending-mail');
    }
}
