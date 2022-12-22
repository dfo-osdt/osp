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
        Schema::create('funders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name_en');
            $table->string('name_fr');
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
