<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;

class SectorSeeder extends Seeder
{
    /**
     * Load the database with the DFO sectors.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/dfo_sectors.csv');

        SimpleExcelReader::create($path)->getRows()->each(function (array $sector): void {
            Sector::updateOrCreate(
                ['name_en' => $sector['name_en']],
                ['name_fr' => $sector['name_fr']]
            );
        });
    }
}
