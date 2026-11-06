<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Peer reviewers become review contributors so editors can be
     * credited too. Rows created before the ORCID role and type
     * defaults existed are backfilled as reviewers of a review.
     */
    public function up(): void
    {
        Schema::rename('manuscript_peer_reviewers', 'manuscript_review_contributors');

        DB::table('manuscript_review_contributors')
            ->whereNull('role')
            ->update(['role' => 'reviewer']);

        DB::table('manuscript_review_contributors')
            ->whereNull('type')
            ->update(['type' => 'review']);
    }
};
