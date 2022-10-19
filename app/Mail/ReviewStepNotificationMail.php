<?php

namespace App\Mail;

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
        $this->previousStep = $managementReviewStep->previousStep;
        $this->manuscriptRecord = $managementReviewStep->manuscriptRecord;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Manuscript management review [action required]: '.$this->managementReviewStep->manuscriptRecord->title);
        $this->to($this->managementReviewStep->user->email, $this->managementReviewStep->user->fullName);
        $this->cc($this->previousStep->user->email, $this->previousStep->user->fullName);
        $this->cc($this->managementReviewStep->manuscriptRecord->user->email, $this->managementReviewStep->manuscriptRecord->user->fullName);
        $this->attach($this->managementReviewStep->manuscriptRecord->getManuscriptFile());

        return $this->markdown('mail.review-step-notification-mail');
    }
}
