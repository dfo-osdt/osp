<?php

use App\Models\Organization;
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
        Schema::table('organizations', function (Blueprint $table) {

            $table->string('ror_identifier')->nullable()->index();
            $table->json('ror_names')->nullable();
            $table->string('country_code')->nullable();

            $table->dropUnique('organizations_name_en_unique');
            $table->dropUnique('organizations_name_fr_unique');

            $table->string('name_en', 255)->change();
            $table->string('name_fr', 255)->change();

        });

        // update Fisheries and Oceans Canada
        $dfo = Organization::where('name_en', 'Fisheries and Oceans Canada')->first();
        if (! $dfo) {
            return;
        } // brand new database
        $dfo->ror_identifier = 'https://ror.org/02qa1x782';
        $dfo->save();

    }
};
