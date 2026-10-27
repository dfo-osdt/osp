<?php

use App\Models\Region;
use App\Models\Sector;
use App\Models\User;

test('pasting a full ORCID URL keeps only the iD', function (): void {
    $user = User::factory()->create();
    $user->author->update(['sector_id' => Sector::factory()->create()->id, 'region_id' => Region::query()->value('id')]);
    $this->actingAs($user);

    $page = visit('/');
    $page->waitForText('Help us')->script("window.location.hash = '#/settings/author'");

    // the browser plugin cannot fill the clipboard, so dispatch the paste event directly
    $page->waitForText('https://orcid.org/')->script(<<<'JS'
        const clipboardData = new DataTransfer()
        clipboardData.setData('text/plain', 'https://orcid.org/0000-0002-1825-0097')
        document.querySelector('[data-test="orcid-input"]')
            .dispatchEvent(new ClipboardEvent('paste', { clipboardData, bubbles: true, cancelable: true }))
    JS);

    $page->assertValue('[data-test="orcid-input"]', '0000-0002-1825-0097')
        ->click('Save')
        ->waitForText('Saved');

    $this->assertDatabaseHas('authors', ['id' => $user->author->id, 'orcid' => 'https://orcid.org/0000-0002-1825-0097']);

    assertNoRealJavascriptErrors($page);
});
