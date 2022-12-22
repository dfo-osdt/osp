<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funding_sources', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('description', 2000)->nullable();
            $table->foreignId('funder_id')->constrained()->cascadeOnDelete();
            // polymorphic relationship
            $table->morphs('fundable');
        });
    }
};
