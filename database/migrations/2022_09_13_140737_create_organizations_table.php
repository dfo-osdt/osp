<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('is_validated')->default(false);
            $table->string('name_en', 255)->index();
            $table->string('name_fr', 255)->index();
            $table->string('abbr_en')->nullable()->index();
            $table->string('abbr_fr')->nullable()->index();
            $table->string('ror_identifier')->nullable()->index();
            $table->string('ror_version')->nullable();
            $table->json('ror_names')->nullable();
            $table->string('country_code')->nullable();
        });
    }
};
