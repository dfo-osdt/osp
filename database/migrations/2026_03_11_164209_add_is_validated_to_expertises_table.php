<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expertises', function (Blueprint $table) {
            $table->boolean('is_validated')->default(false)->after('name_fr');
        });

        DB::table('expertises')->update(['is_validated' => true]);
    }

    public function down(): void
    {
        Schema::table('expertises', function (Blueprint $table) {
            $table->dropColumn('is_validated');
        });
    }
};
