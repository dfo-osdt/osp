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
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn('title_fr');
            $table->renameColumn('title_en', 'title');
            $table->string('issn',10)->nullable()->index();
        });
    }

};
