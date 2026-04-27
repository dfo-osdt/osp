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
        Schema::create('helpful_links', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->string('title_en', 255);
            $table->string('title_fr', 255);
            $table->string('url_en', 255);
            $table->string('url_fr', 255);
            $table->string('description_en', 512);
            $table->string('description_fr', 512);
        });
    }
};
