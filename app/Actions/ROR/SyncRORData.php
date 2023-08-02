<?php

namespace App\Actions\ROR;

use App\Models\Organization;
use Cerbero\JsonParser\JsonParser;

class SyncRORData
{

    public static function handle(string $jsonFilePath, callable $progressCallback = null): void
    {
        if(!file_exists($jsonFilePath)) {
            throw new \Exception('File not found: ' . $jsonFilePath);
        }

        $json = JsonParser::parse($jsonFilePath);

        $countryCodesToImport = collect([
            'CA',
            'US',
            'GB',
            'AU',
            'NZ',
            'FR',
        ]);

        $update = is_callable($progressCallback);

        foreach($json as $key => $record){

            // we only want to import "active" records
            if($record['status'] !== 'active') continue;
            // only import records with a country code we want
            if(!$countryCodesToImport->contains($record['country']['country_code'])) continue;

            // update or create the organization
            self::updateOrCreateOrganization($record);

            if($update) $progressCallback($json->progress()->percentage());

        }
    }

    /**
     * Update or create an organization record - based on ROR metadata schema 1.0
     * which is bad with with internationalization of names, aliases, and acronyms.
     *
     * We will update this routine when ROR updates their schema to 2.0 in late 2023.
     *
     * @param array $record
     * @return void
     */
    private static function updateOrCreateOrganization(array $record)
    {
        $ror_identifier = $record['id'];

        // main org names - only i18n is in labels
        $name_fr = null;
        $name_en = null;

        collect($record['labels'])->each(function($label) use (&$name_fr, &$name_en){
            $iso639 = $label['iso639'] ?? null;
            if(!$iso639) return;

            if($iso639 === 'fr') {
                $name_fr = $label['label'];
            }
            if($iso639 === 'en') {
                $name_en = $label['label'];
            }
        });

        if(!$name_en) $name_en = $record['name'];
        if(!$name_fr) $name_fr = $record['name'];

        // acronyms - no i18n available at this time. Assume EN first.
        $abbr_en = $record['acronyms'][0] ?? null;
        $abbr_fr = $record['acronyms'][1] ?? $abbr_en;

        // bundle up name data for json column
        $ror_names = collect($record['name'])->
            concat($record['labels'])->
            concat($record['acronyms'])->
            concat($record['aliases'])->
            toJson();

        $country_code = $record['country']['country_code'] ?? null;

        // find or create the organization
        $organization = Organization::updateOrCreate(
            ['ror_identifier' => $ror_identifier],
            [
                'name_en' => $name_en,
                'name_fr' => $name_fr,
                'abbr_en' => $abbr_en,
                'abbr_fr' => $abbr_fr,
                'ror_names' => $ror_names,
                'country_code' => $country_code,
                'is_validated' => true
            ]
        );

    }
}