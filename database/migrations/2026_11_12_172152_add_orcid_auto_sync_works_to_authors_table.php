<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Whether the author's publications are added to ORCID as works as soon as
     * they are published. Off by default, for existing authors too.
     */
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->boolean('orcid_auto_sync_works')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn('orcid_auto_sync_works');
        });
    }
};
