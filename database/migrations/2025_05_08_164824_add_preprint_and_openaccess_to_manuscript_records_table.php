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
            $table->string('preprint_url', 512)->nullable();
            $table->boolean('intends_open_access')->default(true);
            $table->text('open_access_rationale')->nullable();
        });
    }
};
