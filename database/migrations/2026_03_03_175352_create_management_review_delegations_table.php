<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('management_review_delegations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('delegate_user_id')->constrained('users');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at');
            $table->dateTime('ended_early_at')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }
};
