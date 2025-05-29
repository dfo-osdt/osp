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
        Schema::table('manuscript_records', function (Blueprint $table) {

            $table->string('pls_source_language')->default('en');
            $table->boolean('pls_approved_by_author')->default(false);
            $table->boolean('pls_translation_approved')->default(false);

        });
    }
};
