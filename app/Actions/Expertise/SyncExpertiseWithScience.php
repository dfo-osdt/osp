<?php

namespace App\Actions\Expertise;

use App\Models\Expertise;
use Illuminate\Support\Facades\Http;

class SyncExpertiseWithScience
{
    /**
     * @param  callable  $message(string):  void
     */
    public static function handle(?callable $message = null): bool
    {
        $url = 'https://profils-profiles.science.gc.ca/api/views/admin_taxonomy_term?display_id=services_1';

        $response = Http::get($url);
        if (! $response->ok()) {
            return false;
        }

        $expertises = collect($response->json());

        // lower case array of keywords to remove in english
        $termsToRemove = [
            'bio-informatics',
            'biological',
            'Forage plant',
        ];

        // Taken from RESE guidance - retreived on: 2023-10-27
        $reseExperties = [
            ['Aquaculture', "L'aquaculture"],
            ['Finfish', 'Poisson à nageoires'],
            ['Pathogens', 'Les agents pathogènes'],
            ['Shellfish', 'Mollusques et crustacés'],
            ['Wild / Cultured interactions', 'Interactions entre les espèces sauvages et les espèces cultivées'],
            ['Biology and Ecology', 'Biologie et écologie'],
            ['Benthic Ecology', 'Écologie benthique'],
            ['Fish Biology', 'Biologie des poissons'],
            ['Invertebrate Biology', 'Biologie des invertébrés'],
            ['Harmful Algal Blooms', 'Efflorescences algales nuisibles'],
            ['Aquatic Invasive Species', 'Espèces aquatiques envahissantes'],
            ['Marine Mammal Biology', 'Biologie des mammifères marins'],
            ['Freshwater Ecology', 'Écologie des eaux douces'],
            ['Ecosystems Features', 'Caractéristiques des écosystèmes'],
            ['Marine Protected Areas', 'Zones marines protégées'],
            ['Cold-water corals and sponges', "Coraux et éponges d'eau froide"],
            ['Hydrothermal vents', 'Foyers hydrothermaux'],
            ['Seamounts', 'Monts sous-marins'],
            ['Eelgrass', 'La zostère'],
            ['Fisheries and Stock Groups', 'Pêcheries et groupes de stocks'],
            ['Management Strategies', 'Stratégies de gestion'],
            ['Stock Assessments', 'Évaluation des stocks'],
            ['Crustaceans', 'Crustacés'],
            ['Groundfish', 'Poissons de fond'],
            ['Large pelagics ', 'Grands pélagiques '],
            ['Other Stock Groups', "Autres groupes d'actions"],
            ['Salmonids', 'Salmonidés'],
            ['Small pelagics', 'Petits pélagiques'],
            ['Survey design', "Conception de l'enquête"],
            ['Marine Mammals', 'Mammifères marins'],
            ['Mollusks', 'Mollusques'],
            ['Genetics', 'Génétique'],
            ['Designatable Units', 'Unités désignables'],
            ['Genomics', 'Génomique'],
            ['Introgression', 'Introgression'],
            ['Stock Structure', 'Structure du stock'],
            ['Habitat', 'Habitat'],
            ['Estuarine', 'Estuaire'],
            ['Freshwater', 'Eau douce'],
            ['Marine', 'Marine'],
            ['Modeling, Statistics and Bioinformatics', 'Modélisation, statistiques et bioinformatique'],
            ['Bioinformatics', 'Bioinformatique'],
            ['Current modeling', 'Modélisation des courants'],
            ['Fisheries modeling', 'Modélisation de la pêche'],
            ['Oceans modeling', 'Modélisation des océans'],
            ['Quantitative modeling', 'Modélisation quantitative'],
            ['Spatial modeling', 'Modélisation spatiale'],
            ['Spatiotemporal statistics', 'Statistiques spatio-temporelles'],
            ['Oceanography', 'Océanographie'],
            ['Biological oceanography', 'Océanographie biologique'],
            ['Ocean chemistry', 'Chimie des océans'],
            ['Ocean monitoring', 'Surveillance des océans'],
            ['Physical chemistry', 'Chimie physique'],
        ];

        // add rese experties to the Science expertise list
        foreach ($reseExperties as $expertise) {
            $expertises->push([
                'name_en' => $expertise[0],
                'name_fr' => $expertise[1],
            ]);
        }

        $unique = $expertises
            ->unique(function ($expertise) {
                return strtolower($expertise['name_en']);
            })
            ->unique(function ($expertise) {
                return strtolower($expertise['name_fr']);
            })
            ->filter(function ($expertise) use ($termsToRemove) {
                $a = strtolower($expertise['name_en']);

                return ! in_array($a, $termsToRemove);
            });

        foreach ($unique as $expertise) {

            try {
                Expertise::updateOrCreate(
                    [
                        'name_en' => html_entity_decode($expertise['name_en']),
                    ],
                    [
                        'name_fr' => html_entity_decode($expertise['name_fr']),
                    ]
                );
            } catch (\Illuminate\Database\UniqueConstraintViolationException) {

                if (is_callable($message)) {
                    $message('Duplicate expertise found: '.$expertise['name_en'].' / '.$expertise['name_fr'].PHP_EOL);
                }

            }
        }

        return true;
    }
}
