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
        Schema::table('publications', function (Blueprint $table) {
            $table->string('isbn', 25)->nullable()->after('doi')->comment('International Standard Book Number');
            $table->string('catalogue_number', 25)->nullable()->after('isbn')->comment('DFO Catalogue Number');
        });
    }
};
