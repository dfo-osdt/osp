<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Whether the author wants the publication on their ORCID record as a work
     * (wanted, declined or null when undecided), when they last decided, and the
     * ORCID work it is synced to.
     */
    public function up(): void
    {
        Schema::table('publication_authors', function (Blueprint $table) {
            $table->string('orcid_sync')->nullable();
            $table->timestamp('orcid_sync_at')->nullable();
            $table->integer('orcid_putcode')->nullable();
            $table->timestamp('orcid_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publication_authors', function (Blueprint $table) {
            $table->dropColumn(['orcid_sync', 'orcid_sync_at', 'orcid_putcode', 'orcid_updated_at']);
        });
    }
};
