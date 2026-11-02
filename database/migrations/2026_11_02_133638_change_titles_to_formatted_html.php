<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Titles now hold formatted HTML (<i>, <sup>, <sub>) up to 1000 characters of text,
     * with a generated plain-text copy for searching and sorting.
     */
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropIndex(['title']);
        });

        foreach (['manuscript_records', 'publications'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->text('title')->change();
            });

            Schema::table($tableName, function (Blueprint $table) {
                $table->text('title_plain')->virtualAs($this->plainTitleExpression())->after('title');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['manuscript_records', 'publications'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('title_plain');
            });

            Schema::table($tableName, function (Blueprint $table) {
                $table->string('title', 500)->change();
            });
        }

        Schema::table('publications', function (Blueprint $table) {
            $table->index('title');
        });
    }

    /**
     * Strip the title tags and decode the only entities a stored title contains.
     * &amp; is decoded last so "&amp;lt;" stays a literal "&lt;".
     */
    private function plainTitleExpression(): string
    {
        $replacements = [
            '<i>' => '', '</i>' => '',
            '<sup>' => '', '</sup>' => '',
            '<sub>' => '', '</sub>' => '',
            '&lt;' => '<', '&gt;' => '>',
            '&amp;' => '&',
        ];

        $expression = 'title';
        foreach ($replacements as $search => $replace) {
            $expression = "REPLACE({$expression}, '{$search}', '{$replace}')";
        }

        return $expression;
    }
};
