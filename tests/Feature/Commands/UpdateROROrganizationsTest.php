<?php

use App\Console\Commands\UpdateROROrganizations;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

/**
 * Build a Zenodo "records" API response pointing at the given file URL/version.
 */
function fakeZenodoResponse(string $fileUrl, string $version): array
{
    return [
        'hits' => [
            'hits' => [
                [
                    'metadata' => ['version' => $version],
                    'files' => [
                        ['links' => ['self' => $fileUrl]],
                    ],
                ],
            ],
        ],
    ];
}

/**
 * Build a zip (as raw bytes) containing a minimal ROR JSON dump.
 */
function fakeRorZipBytes(string $innerJsonName): string
{
    $records = [[
        'id' => 'https://ror.org/test01',
        'status' => 'active',
        'admin' => ['last_modified' => ['date' => '2024-01-01T00:00:00']],
        'names' => [
            ['value' => 'Test University', 'types' => ['ror_display', 'label'], 'lang' => 'en'],
        ],
        'locations' => [
            ['geonames_details' => ['country_code' => 'CA']],
        ],
    ]];

    $zipPath = tempnam(sys_get_temp_dir(), 'ror').'.zip';
    $zip = new ZipArchive;
    $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addFromString($innerJsonName, json_encode($records));
    $zip->close();

    $bytes = file_get_contents($zipPath);
    unlink($zipPath);

    return $bytes;
}

beforeEach(function (): void {
    Storage::fake('local');
});

test('it skips the sync when the latest version is already synced', function (): void {
    Cache::forever(UpdateROROrganizations::VERSION_CACHE_KEY, 'v1.50');

    Http::fake([
        'zenodo.org/api/records*' => Http::response(
            fakeZenodoResponse('https://zenodo.org/api/files/abc/v1.50-ror-data.zip/content', 'v1.50')
        ),
    ]);

    $this->artisan('osp:update-ror-organizations')
        ->expectsOutputToContain('Already up to date')
        ->assertSuccessful();

    // only the metadata lookup should have been made, no file download
    Http::assertSentCount(1);
});

test('it downloads, syncs and cleans up when a new version is detected', function (): void {
    $fileUrl = 'https://zenodo.org/api/files/abc/v1.99-test-ror-data.zip/content';

    Http::fake([
        'zenodo.org/api/records*' => Http::response(fakeZenodoResponse($fileUrl, 'v1.99')),
        'zenodo.org/api/files/*' => Http::response(fakeRorZipBytes('v1.99-test-ror-data.json')),
    ]);

    $this->artisan('osp:update-ror-organizations')
        ->expectsOutputToContain('New ROR data version detected: v1.99')
        ->assertSuccessful();

    expect(Organization::query()->where('ror_identifier', 'https://ror.org/test01')->exists())->toBeTrue()
        ->and(Cache::get(UpdateROROrganizations::VERSION_CACHE_KEY))->toBe('v1.99')
        ->and(Storage::exists('ror_data'))->toBeFalse();
});

test('it keeps temporary files when --keep is passed', function (): void {
    $fileUrl = 'https://zenodo.org/api/files/abc/v1.99-test-ror-data.zip/content';

    Http::fake([
        'zenodo.org/api/records*' => Http::response(fakeZenodoResponse($fileUrl, 'v1.99')),
        'zenodo.org/api/files/*' => Http::response(fakeRorZipBytes('v1.99-test-ror-data.json')),
    ]);

    $this->artisan('osp:update-ror-organizations', ['--keep' => true])
        ->assertSuccessful();

    expect(Storage::exists('ror_data'))->toBeTrue();
});

test('it forces a sync even when the version matches', function (): void {
    Cache::forever(UpdateROROrganizations::VERSION_CACHE_KEY, 'v1.99');

    $fileUrl = 'https://zenodo.org/api/files/abc/v1.99-test-ror-data.zip/content';

    Http::fake([
        'zenodo.org/api/records*' => Http::response(fakeZenodoResponse($fileUrl, 'v1.99')),
        'zenodo.org/api/files/*' => Http::response(fakeRorZipBytes('v1.99-test-ror-data.json')),
    ]);

    $this->artisan('osp:update-ror-organizations', ['--force' => true])
        ->expectsOutputToContain('New ROR data version detected: v1.99')
        ->assertSuccessful();

    expect(Organization::query()->where('ror_identifier', 'https://ror.org/test01')->exists())->toBeTrue();
});

test('it fails when the latest version cannot be determined', function (): void {
    Http::fake([
        'zenodo.org/api/records*' => Http::response(['hits' => ['hits' => []]]),
    ]);

    $this->artisan('osp:update-ror-organizations')
        ->expectsOutputToContain('Unable to determine the latest ROR data version.')
        ->assertExitCode(1);
});
