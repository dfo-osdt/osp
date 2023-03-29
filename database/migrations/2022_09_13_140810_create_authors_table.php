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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name')->index();
            $table->string('last_name')->index();
            $table->string('orcid')->nullable()->index();
            $table->boolean('orcid_verified')->nullable()->index();
            $table->string('orcid_access_token', 1500)->nullable();
            $table->string('email')->unique()->index()->index();
            $table->foreignId('organization_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
        });
    }
};
