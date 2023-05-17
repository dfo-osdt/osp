<?php

namespace App\Mail;

use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email sent to the division manager when a manuscript record is submitted for review.
 * Email has manuscript attached and is cc'd to all authors and user that submitted the manuscript.
 */
class ManuscriptRecordToReviewMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public ManuscriptRecord $manuscriptRecord, public User $user)
    {
        $manuscriptRecord->load('user', 'manuscriptAuthors.author');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Manuscript Record Submitted: '.$this->manuscriptRecord->title);
        $this->to($this->user->email, $this->user->fullName);
        $this->cc($this->manuscriptRecord->user->email, $this->manuscriptRecord->user->fullName);
        $this->cc($this->manuscriptRecord->manuscriptAuthors->pluck('author.email')->toArray());
        $this->attach($this->manuscriptRecord->getLastManuscriptFile());

        return $this->markdown('mail.manuscriptRecord.manuscriptRecordSubmitted');
    }
}
