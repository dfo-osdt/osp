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
        /**
         *  This schema matches the expertise taxonomy from the profiles registry at profils-profiles.science.gc.ca
         *  The tid and uuid originate from the profiles registry and are used to match the expertise to the
         *  profile.Because we cannot ensure integrity of the tid and uuid from the profiles registry,
         *  we index them but cannot use them as foreign keys constraints in our application.
         */
        Schema::create('expertises', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name_en');
            $table->string('name_fr');
            $table->bigInteger('tid')->index();
            $table->uuid('uuid')->index();
            $table->bigInteger('parent_tid')->index()->nullable();
            $table->string('parent_uuid')->index()->nullable();
        });
    }
};
