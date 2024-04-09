<?php

namespace App\Mail;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewStepNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public ManagementReviewStep $previousStep;

    public ManuscriptRecord $manuscriptRecord;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public ManagementReviewStep $managementReviewStep)
    {
        $this->previousStep = $managementReviewStep->previousStep->load('user');
        $this->manuscriptRecord = $managementReviewStep->manuscriptRecord->load('user', 'manuscriptAuthors.author');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Manuscript Management Review [action required] / Révision par la gestion [action requise]: ' . $this->managementReviewStep->manuscriptRecord->title);
        $this->to($this->managementReviewStep->user->email, $this->managementReviewStep->user->fullName);
        $this->cc($this->previousStep->user->email, $this->previousStep->user->fullName);
        if ($this->previousStep->decision !== ManagementReviewStepDecision::FLAGGED) {
            // This would be redundant if the previous step is on hold as this step is being sent back to the proponent.
            $this->cc($this->managementReviewStep->manuscriptRecord->user->email, $this->managementReviewStep->manuscriptRecord->user->fullName);
        }
        $this->attach($this->managementReviewStep->manuscriptRecord->getLastManuscriptFile());

        return $this->markdown('mail.review-step-notification-mail');
    }
}
