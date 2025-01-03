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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title_en', 255);
            $table->string('title_fr', 255);
            $table->string('text_en', 500);
            $table->string('text_fr', 500);
            $table->dateTime('start_at');
            $table->dateTime('end_at');
        });
    }
};
