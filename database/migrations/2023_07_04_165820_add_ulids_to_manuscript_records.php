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
        Schema::table('manuscript_records', function (Blueprint $table) {
            $table->ulid('ulid')->after('id');
        });

        // all models, including those that are soft deleted
        $models = \App\Models\ManuscriptRecord::withTrashed()->get();

        foreach ($models as $model) {
            $model->ulid = \Illuminate\Support\Str::ulid(Carbon\Carbon::parse($model->created_at));
            $model->save();
        }

        Schema::table('manuscript_records', function (Blueprint $table) {
            $table->unique('ulid')->index();
        });
    }
};
