<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FunctionalAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Functional Areas
         *
         * Fisheries Science
         * Aquatic Ecosystem Science
         * Aquatic Animal Health
         * Biotechnology and Genomics
         * Aquaculture Science
         * Oceans and Climate Change Science
         * Hydrographic Services, Data and Science
         * Aquatic invasive species
         *
         */
        $functionalAreas = [
            ['name_en' => 'Fisheries Science', 'name_fr' => 'Sciences halieutiques'],
            ['name_en' => 'Aquatic Ecosystem Science', 'name_fr' => 'Science écosystèmes aquatiques'],
            ['name_en' => 'Aquatic Animal Health', 'name_fr' => 'Santé animaux aquatiques'],
            ['name_en' => 'Biotechnology and Genomics', 'name_fr' => 'Biotechnologie et génomique'],
            ['name_en' => 'Aquaculture Science', 'name_fr' => 'Science de l\'aquaculture'],
            ['name_en' => 'Oceans and Climate Change Science', 'name_fr' => 'Science des océans & changements climatiques'],
            ['name_en' => 'Hydrographic Services, Data and Science', 'name_fr' => 'Services hydrographiques, données et science'],
            ['name_en' => 'Aquatic invasive species', 'name_fr' => 'Espèces aquatiques envahissantes'],
            ['name_en' => 'Species at Risk', 'name_fr' => 'Espèces en péril']
        ];

        foreach ($functionalAreas as $functionalArea) {
            \App\Models\FunctionalArea::create($functionalArea);
        }
    }
}
