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
        Schema::table('authentication_log', function (Blueprint $table) {
            $table->dropIndex('authentication_log_device_id_index');
        });

        Schema::table('authentication_log', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'device_id',
                'device_name',
                'is_trusted',
                'last_activity_at',
                'is_suspicious',
                'suspicious_reason',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('authentication_log', function (Blueprint $table) {
            $table->json('location')->nullable();
            $table->string('device_id')->nullable()->index();
            $table->string('device_name')->nullable();
            $table->boolean('is_trusted')->default(false);
            $table->timestamp('last_activity_at')->nullable();
            $table->boolean('is_suspicious')->default(false);
            $table->string('suspicious_reason')->nullable();
        });
    }
};
