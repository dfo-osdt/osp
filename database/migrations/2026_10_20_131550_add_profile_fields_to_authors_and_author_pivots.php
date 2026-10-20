<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the DFO sector, region and emeritus status to authors, and
     * copy them on manuscript and publication authors so the values
     * at the time of authorship are kept.
     *
     * Existing DFO rows get emeritus = false; sector and region stay
     * null until the author first fills them in their profile.
     */
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->foreignId('sector_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('region_id')->nullable()->constrained()->restrictOnDelete();
            $table->boolean('is_emeritus')->default(false);
        });

        foreach (['manuscript_authors', 'publication_authors'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('sector_id')->nullable()->constrained()->restrictOnDelete();
                $table->foreignId('region_id')->nullable()->constrained()->restrictOnDelete();
                $table->boolean('is_emeritus')->nullable();
            });
        }

        $defaultOrganizationId = DB::table('organizations')
            ->where('name_en', config('osp.default_organization'))
            ->value('id');

        if ($defaultOrganizationId === null) {
            return;
        }

        foreach (['manuscript_authors', 'publication_authors'] as $tableName) {
            DB::table($tableName)
                ->where('organization_id', $defaultOrganizationId)
                ->update(['is_emeritus' => false]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['authors', 'manuscript_authors', 'publication_authors'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('sector_id');
                $table->dropConstrainedForeignId('region_id');
                $table->dropColumn('is_emeritus');
            });
        }
    }
};
