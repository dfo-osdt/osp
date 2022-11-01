<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Journals. In the context of this application a journal is
     * any scientific publication (journal, series, etc.). Secondary publications will
     * be identified with DFO as the publisher. It is highly unlikely that a journal
     * will have a bilingual title. but we will add it here for DFO series.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title_en', 255);
            $table->string('title_fr', 255)->nullable();
            $table->string('scopus_source_record_id', 50)->nullable()->comment('Scopus source record ID');
            $table->string('publisher', 255);

            // add index for title_en
            $table->index('title_en');
        });
    }
};
