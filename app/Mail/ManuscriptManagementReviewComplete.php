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
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Manuscript Record Management Review Complete: '.$this->manuscriptRecord->title);
        $this->to($this->manuscriptRecord->user->email, $this->user->fullName);
        // all reviewers get confirmation
        $this->cc($this->manuscriptRecord->managementReviewSteps()->with('user')->get()->pluck('user.email')->toArray());
        // all authors get confirmation, make sure not to duplicate the user if an author.
        $this->cc($this->manuscriptRecord->manuscriptAuthors->pluck('author.email')->forget($this->user->email)->toArray());

        return $this->markdown('mail.manuscriptRecord.manuscript-management-review-complete');
    }
}
