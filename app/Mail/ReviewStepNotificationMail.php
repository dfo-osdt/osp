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
        $this->subject('Manuscript Management Review [action required] / Révision par la gestion [action requise]: '.$this->managementReviewStep->manuscriptRecord->title);
        $this->to($this->managementReviewStep->user->email, $this->managementReviewStep->user->fullName);

        $ccEmails = collect();

        // previous step reviewer
        $ccEmails->push($this->previousStep->user->email);

        // manuscript author (unless previous decision was REVISION — would be redundant)
        if ($this->previousStep->decision !== ManagementReviewStepDecision::REVISION) {
            $ccEmails->push($this->manuscriptRecord->user->email);
        }

        // notification group members of the current step's user
        $ccEmails = $ccEmails->merge($this->managementReviewStep->user->getNotificationGroupEmails());

        // all completed reviewers for this manuscript
        $completedReviewerEmails = $this->manuscriptRecord
            ->managementReviewSteps()
            ->where('status', ManagementReviewStepStatus::COMPLETED)
            ->with('user')
            ->get()
            ->pluck('user.email');

        $ccEmails = $ccEmails->merge($completedReviewerEmails);

        // deduplicate and remove the TO recipient
        $ccEmails = $ccEmails
            ->unique()
            ->diff([$this->managementReviewStep->user->email])
            ->values();

        if ($ccEmails->isNotEmpty()) {
            $this->cc($ccEmails->all());
        }

        return $this->markdown('mail.review-step-notification-mail');
    }
}
