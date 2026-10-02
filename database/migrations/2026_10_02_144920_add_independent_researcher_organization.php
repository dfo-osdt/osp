<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Create the sentinel organization used as the affiliation of
     * independent researchers (retired, between positions, etc.).
     * The name must match config('osp.independent_organization').
     *
     * Fresh databases are skipped: the OrganizationSeeder creates the
     * row after the default organization so the latter keeps id 1.
     */
    public function up(): void
    {
        if (DB::table('organizations')->doesntExist()) {
            return;
        }

        $exists = DB::table('organizations')
            ->where('name_en', 'Independent Researcher')
            ->whereNull('ror_identifier')
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('organizations')->insert([
            'name_en' => 'Independent Researcher',
            'name_fr' => 'Chercheur(se) indépendant(e)',
            'is_validated' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
