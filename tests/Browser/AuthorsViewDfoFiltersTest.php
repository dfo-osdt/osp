<?php

use App\Models\Author;
use App\Models\Organization;
use App\Models\User;
use Pest\Browser\Api\PendingAwaitablePage;

/**
 * Open the authors page with the given query string once the app has
 * loaded, since the app sends a cold hash URL to the home page.
 */
function visitAuthors(string $query): PendingAwaitablePage
{
    $page = visit('/');
    $page->waitForText('Author Profile')->script("window.location.hash = '#/authors?{$query}'");

    return $page;
}

test('the DFO sector, region and emeritus filters are hidden and cleared when another organization is chosen', function (): void {
    $this->actingAs(User::factory()->create());
    $dfoAuthor = Author::factory()->withCompleteProfile()->create();
    $organization = Organization::factory()->create(['name_en' => 'Lakeside Fisheries Institute']);
    $externalAuthor = Author::factory()->create(['organization_id' => $organization->id]);

    $page = visitAuthors('org='.Organization::getDefaultOrganization()->id.'&sector='.$dfoAuthor->sector_id);

    $page->click('Filters')
        ->waitForText($dfoAuthor->last_name)
        ->assertPresent('[data-test="sector-filter"]')
        ->assertPresent('[data-test="region-filter"]')
        ->assertPresent('[data-test="emeritus-filter"]')
        ->type('[data-test="organization-filter"]', 'Lakeside')
        ->click('Lakeside Fisheries Institute')
        ->waitForText($externalAuthor->last_name)
        ->assertMissing('[data-test="sector-filter"]')
        ->assertMissing('[data-test="region-filter"]')
        ->assertMissing('[data-test="emeritus-filter"]');
    expect($page->script('window.location.hash'))->not->toContain('sector=');

    assertNoRealJavascriptErrors($page);
});

test('a link with DFO-only filters for another organization ignores them', function (): void {
    $this->actingAs(User::factory()->create());
    $dfoAuthor = Author::factory()->withCompleteProfile()->create();
    $externalAuthor = Author::factory()->create();

    $page = visitAuthors('org='.$externalAuthor->organization_id.'&sector='.$dfoAuthor->sector_id);

    $page->click('Filters')
        ->waitForText($externalAuthor->last_name)
        ->assertMissing('[data-test="sector-filter"]');

    assertNoRealJavascriptErrors($page);
});
