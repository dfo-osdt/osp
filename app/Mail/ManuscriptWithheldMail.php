<?php

namespace App\Mail;

use App\Models\ManuscriptRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ManuscriptWithheldMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $reviewUsers;

    public $authors;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public ManuscriptRecord $manuscriptRecord)
    {
        $this->user = $manuscriptRecord->user;
        $this->reviewUsers = $manuscriptRecord->managementReviewSteps()->with('user')->get()->pluck('user');
        $this->authors = $manuscriptRecord->manuscriptAuthors()->with('author')->get()->pluck('author');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Manuscript Withheld by Management Review: '.$this->manuscriptRecord->title);
        $this->to($this->user->email, $this->user->fullName);
        $this->cc(
            $this->authors->pluck('email')->
            merge($this->reviewUsers->pluck('email'))->
            unique()->
            filter(fn ($email) => $email !== $this->user->email)-> // don't send to the user twice
            filter(fn ($email) => Str::of($email)->contains(config('osp.allowed_registration_email_domains')))-> // only send to authorized email domains
            toArray());

        return $this->markdown('mail.manuscriptRecord.manuscript-withheld-mail');
    }
}
