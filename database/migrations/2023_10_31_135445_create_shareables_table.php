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
        Schema::create('shareables', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->index();
            $table->foreignId('shared_by')->nullable()->constrained('users')->nullOnDelete();
            $table->morphs('shareable');
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->dateTime('expires_at')->nullable();
        });
    }
};
