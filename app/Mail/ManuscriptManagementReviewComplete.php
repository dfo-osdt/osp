<?php

namespace App\Mail;

use App\Models\ManuscriptRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManuscriptManagementReviewComplete extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public ManuscriptRecord $manuscriptRecord)
    {
        $manuscriptRecord->load('user', 'manuscriptAuthors.author', 'managementReviewSteps.user');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Management Review Complete - Révision de gestion complétée : '.$this->manuscriptRecord->title);
        $this->to($this->manuscriptRecord->user->email, $this->manuscriptRecord->user->fullName);
        // all reviewers get confirmation
        $this->cc($this->manuscriptRecord->managementReviewSteps()->with('user')->get()->pluck('user.email')->toArray());
        // all authors get confirmation, make sure not to duplicate the user if an author.
        $this->cc($this->manuscriptRecord->manuscriptAuthors->pluck('author.email')->forget($this->manuscriptRecord->user->email)->toArray());

        return $this->markdown('mail.manuscriptRecord.manuscript-management-review-complete');
    }
}
