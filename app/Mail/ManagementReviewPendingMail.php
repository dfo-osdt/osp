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
     * @var array<int>
     */
    public array $reviewIds;

    /**
     * Create a new message instance.
     *
     * @param  Collection<int, ManagementReviewStep>  $reviews
     */
    public function __construct($reviews, public User $user)
    {
        // Store only the IDs to avoid serialization issues
        $this->reviewIds = $reviews->pluck('id')->toArray();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Load reviews with relationships when building the email
        $reviews = ManagementReviewStep::query()
            ->whereIn('id', $this->reviewIds)
            ->with('manuscriptRecord')
            ->get();

        $subject = 'Weekly Summary: Pending Management Reviews / Résumé hebdomadaire: Révisions de gestion en attente';

        $this->subject($subject);
        $this->to($this->user->email, $this->user->fullName);

        $notificationGroupEmails = $this->user->getNotificationGroupEmails();
        if ($notificationGroupEmails->isNotEmpty()) {
            $this->cc($notificationGroupEmails->all());
        }

        return $this->markdown('mail.management-review-pending-mail', [
            'reviews' => $reviews,
            'user' => $this->user,
        ]);
    }
}
