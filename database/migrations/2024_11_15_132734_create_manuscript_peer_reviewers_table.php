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
        Schema::create('manuscript_peer_reviewers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('manuscript_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->string('type', 25)->nullable();
            $table->string('role', 25)->nullable();
            $table->date('review_date')->nullable();
        });
    }
};
