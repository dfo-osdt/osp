<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('member_user_id')->constrained('users');
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'member_user_id']);
        });
    }
};
