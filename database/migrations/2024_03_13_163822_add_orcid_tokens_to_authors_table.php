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
        Schema::table('authors', function (Blueprint $table) {
            $table->string('orcid_access_token', 500)->nullable()->change();
            $table->string('orcid_token_scope', 500)->nullable()->after('orcid_access_token');
            $table->string('orcid_refresh_token', 500)->nullable()->after('orcid_token_scope');
            $table->dateTime('orcid_expires_at')->nullable()->after('orcid_refresh_token');
        });
    }
};
