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
        Schema::create('author_employments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('orcid_putcode')->nullable();
            $table->timestamp('orcid_updated_at')->nullable();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('role_title')->nullable();
            $table->string('department_name')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
        });
    }

};
