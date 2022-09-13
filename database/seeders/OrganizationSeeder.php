<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = SimpleExcelReader::create('database/seeders/data/organizations.csv')->getRows();

        $organizations->each(function ($organization) {
            Organization::firstOrCreate([
                'name_en' => $organization['name_en'],
                'name_fr' => $organization['name_fr'],
                'abbr_en' => $organization['abbr_en'],
                'abbr_fr' => $organization['abbr_fr'],
                'is_validated' => true,
            ]);
        });
    }
}
