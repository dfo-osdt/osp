<?php

namespace App\Actions\Expertise;

use App\Models\Expertise;
use Cerbero\JsonParser\JsonParser;
use Illuminate\Support\Facades\Http;

class SyncExpertiseWithScience
{
    public static function handle(): bool
    {
        $url = 'https://profils-profiles.science.gc.ca/api/views/admin_taxonomy_term?display_id=services_1';

        $response = Http::get($url);
        if (!$response->ok()) {
            return false;
        }

        $json = JsonParser::parse($response);

        foreach ($json as $key => $record) {
            Expertise::updateOrCreate([
                'tid' => $record['tid'],
            ], [
                'name_en' => htmlspecialchars_decode($record['name_en']),
                'name_fr' => htmlspecialchars_decode($record['name_fr']),
                'parent_tid' => $record['parent_tid'] === '' ? null : $record['parent_tid'],
                'parent_uuid' => $record['parent_uuid'],
                'uuid' => $record['uuid'],
            ]);
        }

        return true;
    }
}
