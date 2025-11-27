<?php

namespace App\Actions\ROR;

use App\Models\Organization;
use Cerbero\JsonParser\JsonParser;

class SyncRORData
{
    public static function handle(string $jsonFilePath, string $version, ?callable $progressCallback = null): void
    {
        if (! file_exists($jsonFilePath)) {
            throw new \Exception('File not found: '.$jsonFilePath);
        }

        $json = JsonParser::parse($jsonFilePath);

        $countryCodesToImport = collect([
            'CA',
            'US',
            'GB',
            'AU',
            'NZ',
            'FR',
            'DE',
            'DK',
            'NO',
        ]);

        $update = is_callable($progressCallback);

        foreach ($json as $record) {

            // we only want to import "active" records
            if ($record['status'] !== 'active') {
                continue;
            }
            // only import records with a country code we want
            if ($countryCodesToImport->doesntContain($record['locations'][0]['geonames_details']['country_code'])) {
                continue;
            }

            // update or create the organization
            self::updateOrCreateOrganization($record, $version);

            if ($update) {
                $progressCallback($json->progress()->percentage());
            }

        }
    }

    /**
     * Update or create an organization record - based on ROR metadata schema 1.0
     * which is bad with with internationalization of names, aliases, and acronyms.
     *
     * We will update this routine when ROR updates their schema to 2.0 in late 2023.
     */
    private static function updateOrCreateOrganization(array $record, string $version): void
    {
        $ror_identifier = $record['id'];

        $lastModified = $record['admin']['last_modified']['date'];
        $lastModifiedAt = \Illuminate\Support\Facades\Date::parse($lastModified);

        // first - check if we have this record already and if version is the same
        $alreadyUpToDate = Organization::where('ror_identifier', $ror_identifier)->where('updated_at', $lastModified)->exists();
        if ($alreadyUpToDate) {
            return;
        }

        // get default name - ror_display
        $ror_display_name = collect($record['names'])->filter(fn (array $name): bool => in_array('ror_display', $name['types']))->first()['value'] ?? null;

        // if there's no ror display name, throw an error
        if (! $ror_display_name) {
            throw new \Exception('No ror_display name found for ROR ID: '.$ror_identifier);
        }

        // get english and french names
        $name_en = collect($record['names'])->filter(fn (array $name): bool => in_array('label', $name['types']) && $name['lang'] === 'en')->first()['value'] ?? $ror_display_name;

        $name_fr = collect($record['names'])->filter(fn (array $name): bool => in_array('label', $name['types']) && $name['lang'] === 'fr')->first()['value'] ?? $ror_display_name;

        // acronyms - ROR 2.0 datasets doens't have lang well implemeted on acronyms yet. Assume EN first.
        $acronyms = collect($record['names'])->filter(fn (array $name): bool => in_array('acronym', $name['types']))->pluck('value')->toArray();

        // try to get english and french acronyms
        $abbr_en = collect($record['names'])->filter(fn (array $name): bool => in_array('acronym', $name['types']) && $name['lang'] === 'en')->first()['value'] ?? $acronyms[0] ?? null;

        $abbr_fr = collect($record['names'])->filter(fn (array $name): bool => in_array('acronym', $name['types']) && $name['lang'] === 'fr')->first()['value'] ?? $acronyms[1] ?? $abbr_en;

        // bundle up name data for json column
        $ror_names = collect($record['names'])->toJson();

        $country_code = $record['locations'][0]['geonames_details']['country_code'] ?? null;

        // find or create the organization
        Organization::updateOrCreate(
            ['ror_identifier' => $ror_identifier],
            [
                'name_en' => $name_en,
                'name_fr' => $name_fr,
                'abbr_en' => $abbr_en,
                'abbr_fr' => $abbr_fr,
                'ror_names' => $ror_names,
                'country_code' => $country_code,
                'is_validated' => true,
                'ror_version' => $version,
                'updated_at' => $lastModifiedAt,
            ]
        );

    }
}
