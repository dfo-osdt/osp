<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::hasColumn('manuscript_records', 'scientific_implications') && Schema::table('manuscript_records', function (Blueprint $table) {
            $table->dropColumn('scientific_implications');
        });

        Schema::hasColumn('manuscript_records', 'regions_and_species') && Schema::table('manuscript_records', function (Blueprint $table) {
            $table->dropColumn('regions_and_species');
        });
    }
};
