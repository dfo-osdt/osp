<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * When the author last decided whether they want the contribution on ORCID,
     * apart from other changes to the contribution (e.g. its role).
     */
    public function up(): void
    {
        Schema::table('manuscript_review_contributors', function (Blueprint $table) {
            $table->timestamp('orcid_sync_at')->nullable()->after('orcid_sync');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manuscript_review_contributors', function (Blueprint $table) {
            $table->dropColumn('orcid_sync_at');
        });
    }
};
