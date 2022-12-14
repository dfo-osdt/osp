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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('status', 20)->comment('accepted, published, etc.');
            $table->string('title', 500)->index();
            $table->string('doi')->nullable()->index();
            $table->date('accepted_on')->nullable();
            $table->date('published_on')->nullable();
            $table->date('embargoed_until')->nullable();
            $table->boolean('is_open_access')->default(false);

            $table->foreignId('manuscript_record_id')->nullable()->constrained();
            $table->foreignId('journal_id')->constrained();
            $table->foreignId('user_id')->constrained();
        });
    }
};
