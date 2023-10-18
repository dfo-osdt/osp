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
        /**
         * Pivot table for author expertise.
         */
        Schema::create('author_expertise', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->foreignId('expertise_id')->constrained()->onDelete('cascade');
            // enforce uniqueness of author/expertise pairs at the database level
            $table->unique(['author_id', 'expertise_id']);
        });
    }
};
