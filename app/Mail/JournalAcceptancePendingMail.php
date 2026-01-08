<?php

namespace App\Mail;

use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JournalAcceptancePendingMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @var array<int>
     */
    public array $manuscriptIds;

    /**
     * Create a new message instance.
     *
     * @param  \Illuminate\Support\Collection<int, ManuscriptRecord>  $manuscripts
     * @return void
     */
    public function __construct($manuscripts, public User $user)
    {
        // Store only the IDs to avoid serialization issues
        $this->manuscriptIds = $manuscripts->pluck('id')->toArray();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Load manuscripts when building the email
        $manuscripts = ManuscriptRecord::query()
            ->whereIn('id', $this->manuscriptIds)
            ->get();

        $subject = 'Monthly Reminder: Update Your Manuscript Status / Rappel mensuel: Mettre à jour le statut de vos manuscrits';

        $this->subject($subject);
        $this->to($this->user->email, $this->user->fullName);

        return $this->markdown('mail.journal-acceptance-pending-mail', [
            'manuscripts' => $manuscripts,
            'user' => $this->user,
        ]);
    }
}
