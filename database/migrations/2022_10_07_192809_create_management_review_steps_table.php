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
        Schema::create('management_review_steps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('manuscript_record_id')->cascadeOnDelete();
            $table->foreignId('previous_step_id')->nullable()->references('id')->on('management_review_steps');
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('completed_at')->nullable();
            $table->text('comments')->nullable();
            $table->string('status', 20);
            $table->string('decision', 20)->nullable();
        });
    }
};
