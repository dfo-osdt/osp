<?php

use App\Events\ManuscriptRecordWithheldByManagement;
use App\Mail\ManuscriptWithheldMail;
use App\Models\ManuscriptRecord;

test('test listener works and queues mails', function () {
    Mail::fake();

    ManuscriptRecordWithheldByManagement::dispatch(
        ManuscriptRecord::factory()->withheld()->create(),
    );

    Mail::assertQueued(ManuscriptWithheldMail::class);
});
