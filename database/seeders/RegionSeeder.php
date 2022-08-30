<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $regions = SimpleExcelReader::create('database/seeders/data/dfo_regions.csv')->getRows();

        $regions->each(function ($region) {
            Region::firstOrCreate([
                'name_en' => $region['name_en'],
                'name_fr' => $region['name_fr'],
            ]);
        });
    }
}
