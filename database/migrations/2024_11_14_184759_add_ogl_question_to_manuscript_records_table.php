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
            $table->boolean('apply_ogl')->default(true);
            $table->text('no_ogl_explanation')->nullable();
            $table->renameColumn('additional_information', 'public_interest_information');
        });
    }
};
