<?php

namespace App\Mail;

use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ManagementReviewDueMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  Collection<int, ManagementReviewStep>  $reviews
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
        $overdueCount = $this->reviews->filter(fn ($review): bool => $review->decision_expected_by < now())->count();
        $dueSoonCount = $this->reviews->count() - $overdueCount;

        if ($overdueCount > 0 && $dueSoonCount > 0) {
            $subject = 'Management Reviews Due & Overdue / Révisions de gestion à échéance et en retard';
        } elseif ($overdueCount > 0) {
            $subject = 'Management Reviews Overdue / Révisions de gestion en retard';
        } else {
            $subject = 'Management Reviews Due Soon / Révisions de gestion à échéance';
        }

        $this->subject($subject);
        $this->to($this->user->email, $this->user->fullName);

        return $this->markdown('mail.management-review-due-mail');
    }
}
