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
        // get full system path to dfo_report_series.csv file
        $path = database_path('seeders/data/dfo_report_series.csv');

        $series = SimpleExcelReader::create($path)->getRows();

        $series->each(function ($serie) {
            Journal::firstOrCreate([
                'issn' => $serie['issn'],
            ], [
                'title' => $serie['title'],
                'publisher' => $serie['publisher'],
            ]);
        });
    }
}
