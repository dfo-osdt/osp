<?php

use App\Actions\ROR\SyncRORData;
use App\Models\Organization;
use Spatie\Activitylog\Models\Activity;

/**
 * @return array<string, mixed>
 */
function rorRecord(string $id, string $name): array
{
    return [
        'id' => $id,
        'status' => 'active',
        'admin' => ['last_modified' => ['date' => '2026-01-01']],
        'names' => [
            ['value' => $name, 'types' => ['ror_display', 'label'], 'lang' => 'en'],
        ],
        'locations' => [['geonames_details' => ['country_code' => 'CA']]],
    ];
}

test('the ROR sync skips records using the reserved independent researcher name', function (): void {
    $path = sys_get_temp_dir().'/ror-'.uniqid().'.json';
    file_put_contents($path, json_encode([
        rorRecord('https://ror.org/00000test', 'Independent Researcher'),
        rorRecord('https://ror.org/00000good', 'Some University'),
    ]));

    SyncRORData::handle($path, 'v1');

    expect(Organization::query()->where('ror_identifier', 'https://ror.org/00000test')->exists())->toBeFalse()
        ->and(Organization::query()->where('ror_identifier', 'https://ror.org/00000good')->exists())->toBeTrue()
        ->and(Activity::query()->where('description', 'ROR record skipped: name reserved for independent researchers')->exists())->toBeTrue();

    unlink($path);
});
