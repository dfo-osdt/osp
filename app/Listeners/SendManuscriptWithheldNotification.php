<?php

namespace App\Listeners;

use App\Events\ManuscriptRecordWithheldByManagement;
use App\Mail\ManuscriptWithheldMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendManuscriptWithheldNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  App\Events\ManuscriptRecordWithheldByManagement  $event
     * @return void
     */
    public function handle(ManuscriptRecordWithheldByManagement $event)
    {
        Mail::queue(new ManuscriptWithheldMail($event->manuscriptRecord));
    }
}
