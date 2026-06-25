<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

test('it logs a sent email to the activity log with recipients and subject', function (): void {
    Mail::raw('The full body of the message', function ($message): void {
        $message->to('reviewer@example.com', 'Reviewer One')
            ->cc('group@example.com')
            ->subject('Management Reviews Overdue');
    });

    $activity = Activity::query()->inLog('email')->latest('id')->first();

    expect($activity)->not->toBeNull()
        ->and($activity->description)->toBe('Email sent')
        ->and($activity->event)->toBe('sent')
        ->and($activity->properties->get('subject'))->toBe('Management Reviews Overdue')
        ->and($activity->properties->get('to'))->toContain('"Reviewer One" <reviewer@example.com>')
        ->and($activity->properties->get('cc'))->toContain('group@example.com')
        ->and($activity->properties->get('message_id'))->toBeString();
});

test('it does not persist the email body', function (): void {
    Mail::raw('a secret token=abc123 in the body', function ($message): void {
        $message->to('reviewer@example.com')->subject('Reset');
    });

    $activity = Activity::query()->inLog('email')->latest('id')->first();

    expect($activity->properties->has('text'))->toBeFalse()
        ->and($activity->properties->has('html'))->toBeFalse();
});
