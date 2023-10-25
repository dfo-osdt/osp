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

        $expertises = collect($response->json());

        // lower case array of keywords to remove in english
        $termsToRemove = [
            'bio-informatics',
            'biological',
        ];


        $unique = $expertises
            ->unique(function ($expertise) {
                return strtolower($expertise['name_en']);
            })
            ->unique(function ($expertise) {
                return strtolower($expertise['name_fr']);
            })
            ->filter(function ($expertise) use ($termsToRemove) {
                $a = strtolower($expertise['name_en']);
                return !in_array($a, $termsToRemove);
            });


        foreach ($unique as $expertise) {
            Expertise::updateOrCreate(
                [
                    'name_en' => $expertise['name_en'],
                ],
                [
                    'name_fr' => $expertise['name_fr'],
                ]
            );
        }

        return true;
    }
}
