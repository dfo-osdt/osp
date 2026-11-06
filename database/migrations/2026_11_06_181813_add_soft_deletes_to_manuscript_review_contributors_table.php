<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A removed contributor is soft deleted until its ORCID peer review is deleted.
     */
    public function up(): void
    {
        Schema::table('manuscript_review_contributors', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manuscript_review_contributors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
