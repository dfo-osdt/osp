<?php

namespace Database\Seeders;

use App\Models\Journal;
use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;

class DfoSeriesJournalSeeder extends Seeder
{
    /**
     * Load the database with the DFO regions.
     *
     * @return void
     */
    public function run()
    {
        // get full system path to dfo_regions.csv file
        $path = database_path('seeders/data/dfo_report_series.csv');

        $regions = SimpleExcelReader::create($path)->getRows();

        $regions->each(function ($region) {
            Journal::firstOrCreate([
                'title_en' => $region['title_en'],
                'title_fr' => $region['title_fr'],
                'publisher' => $region['publisher'],
            ]);
        });
    }
}
