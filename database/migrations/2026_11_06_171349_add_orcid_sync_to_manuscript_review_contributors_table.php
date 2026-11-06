<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Whether the author wants the contribution on their ORCID record (wanted,
     * declined or null when undecided), and the ORCID peer review it is synced to.
     */
    public function up(): void
    {
        Schema::table('manuscript_review_contributors', function (Blueprint $table) {
            $table->string('orcid_sync')->nullable();
            $table->integer('orcid_putcode')->nullable();
            $table->timestamp('orcid_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manuscript_review_contributors', function (Blueprint $table) {
            $table->dropColumn(['orcid_sync', 'orcid_putcode', 'orcid_updated_at']);
        });
    }
};
