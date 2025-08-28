<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;

class RegionSeeder extends Seeder
{
    /**
     * Load the database with the DFO regions.
     *
     * @return void
     */
    public function run()
    {
        // get full system path to dfo_regions.csv file
        $path = database_path('seeders/data/dfo_regions.csv');

        $regions = SimpleExcelReader::create($path)->getRows();

        $regions->each(function ($region) {
            Region::updateOrCreate(
                ['name_en' => $region['name_en']],
                [
                    'name_fr' => $region['name_fr'],
                    'slug' => $region['slug'],
                ]
            );
        });
    }
}
