<?php

use App\Models\Announcement;

test('an unauthenticated use can query the announcment page', function () {

    $response = $this->get('/api/announcements');
    $response->assertStatus(204);

    Announcement::factory()->create([
        'title_en' => 'Test Title',
        'title_fr' => 'Test Title',
        'text_en' => 'Test Text',
        'text_fr' => 'Test Text',
        'start_at' => now()->subDay(),
        'end_at' => now()->addDay(),
    ]);

    // make anohther innactive announcement
    Announcement::factory()->create([
        'title_en' => 'Old Test Title',
        'title_fr' => 'Test Title',
        'text_en' => 'Test Text',
        'text_fr' => 'Test Text',
        'start_at' => now()->subDays(2),
        'end_at' => now()->subDay(),
    ]);

    $response = $this->get('/api/announcements');
    $response->assertStatus(200);

    expect($response->json('data.0.data.title_en'))->toBe('Test Title');
    $response->assertJsonCount(1, 'data');

});
