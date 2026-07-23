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
        Schema::create('region_notification_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->unique(['region_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_notification_group_members');
    }
};
