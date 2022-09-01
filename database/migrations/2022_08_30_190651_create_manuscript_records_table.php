<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for the manuscript records table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manuscript_records', function (Blueprint $table) {
            // automatic fields
            $table->id();
            $table->timestamps();

            // required fields
            $table->string('type', 20)->comment('primary, secondary, etc.');
            $table->string('status', 20)->comment('draft, submitted, etc.');
            $table->string('title', 255);
            $table->foreignId('region_id')->constrained()->comment('lead region id');
            $table->foreignId('user_id')->constrained()->comment('owner of this record');

            // optional (nullable) fields
            $table->text('abstract')->nullable();
            $table->text('pls_en')->nullable()->comment('Plain Language Summary (English)');
            $table->text('pls_fr')->nullable()->comment('Plain Language Summary (French)');
            $table->text('scientific_implications')->nullable();
            $table->text('regions_and_species')->nullable();
            $table->text('relevant_to')->nullable();
            $table->text('additional_information')->nullable();

            $table->datetime('sent_for_review_at')->nullable();
            $table->datetime('reviewed_at')->nullable();
            $table->date('submitted_to_journal_on')->nullable();
            $table->date('accepted_on')->nullable();
            $table->date('withdrawn_on')->nullable();

        });
    }
};
