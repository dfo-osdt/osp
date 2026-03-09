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
        Schema::table('manuscript_authors', function (Blueprint $table) {
            $table->boolean('is_group_author')->default(false)->after('is_corresponding_author');
        });

        Schema::table('publication_authors', function (Blueprint $table) {
            $table->boolean('is_group_author')->default(false)->after('is_corresponding_author');
        });
    }
};
