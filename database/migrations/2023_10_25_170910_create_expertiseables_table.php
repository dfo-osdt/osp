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
        Schema::create('expertiseables', function (Blueprint $table) {
            $table->foreignUlid('expertise_id')->constrained()->cascadeOnDelete();
            $table->morphs('expertiseable');
        });
    }
};
