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
        Schema::create('planning_binder_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->timestamps();
            $table->morphs('plannable');
            $table->string('type');
            $table->string('status');
            $table->text('notes')->nullable();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->date('anticipated_date_of_publication')->nullable();
            $table->string('communication_approach')->nullable();
        });
    }
};
