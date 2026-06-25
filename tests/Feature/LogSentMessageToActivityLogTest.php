<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

test('it logs a sent email to the activity log with recipients and text body', function (): void {
    Mail::raw('The full body of the message', function ($message): void {
        $message->to('reviewer@example.com', 'Reviewer One')
            ->cc('group@example.com')
            ->subject('Management Reviews Overdue');
    });

    $activity = Activity::inLog('email')->latest('id')->first();

    expect($activity)->not->toBeNull()
        ->and($activity->description)->toBe('Email sent')
        ->and($activity->properties->get('subject'))->toBe('Management Reviews Overdue')
        ->and($activity->properties->get('text'))->toContain('The full body of the message')
        ->and($activity->properties->get('to'))->toContain('"Reviewer One" <reviewer@example.com>')
        ->and($activity->properties->get('cc'))->toContain('group@example.com')
        ->and($activity->properties->get('message_id'))->toBeString();
});
