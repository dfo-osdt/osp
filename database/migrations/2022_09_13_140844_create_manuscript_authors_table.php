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
        Schema::create('manuscript_authors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('manuscript_record_id')->constrained();
            $table->foreignId('author_id')->constrained();
            $table->foreignId('organization_id')->constrained();
            $table->boolean('is_corresponding_author')->default(false);
        });
    }
};
