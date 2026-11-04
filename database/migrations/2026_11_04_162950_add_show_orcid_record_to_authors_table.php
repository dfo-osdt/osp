<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Whether the author's ORCID record is shown on their author profile.
     * On by default, for existing authors too.
     */
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->boolean('show_orcid_record')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn('show_orcid_record');
        });
    }
};
