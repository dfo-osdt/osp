<?php

namespace Database\Seeders;

use App\Models\Funder;
use Illuminate\Database\Seeder;

class FunderSeeder extends Seeder
{
    /**
     * Run the database seeds for the initial 'funders' table.
     *
     * @return void
     */
    public function run()
    {
        Funder::create([
            'name_en' => 'Competitive Science Research Fund (CSRF)',
            'name_fr' => 'Fonds de recherche scientifique compétifif (FRSC)',
            'organization_id' => 1,
        ]);
        Funder::create([
            'name_en' => 'Aquaculture Collaborative Research and Development Program (ACRDP)',
            'name_fr' => 'Programme de recherche et développement collaboratif en aquaculture (PRDCA)',
            'organization_id' => 1,
        ]);
        Funder::create([
            'name_en' => 'Science Micro-innovation Funding Initiative (SMFI)',
            'name_fr' => 'Initiative de financement de la micro-innovation des sciences (FMIS)',
            'organization_id' => 1,
        ]);
        Funder::create([
            'name_en' => 'Directed Research Funds - A/B-Base',
            'name_fr' => 'Fonds de recherche dirigée - A/B-Base',
            'organization_id' => 1,
        ]);
        Funder::create([
            'name_en' => 'Other - please specify',
            'name_fr' => 'Autre - veuillez spécifier',
        ]);
    }
}
