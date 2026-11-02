<?php

use App\Services\TitleMarkup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Rewrite titles saved before they were formatted HTML into the canonical form
     * the SanitizedTitle cast writes today (e.g. "&" becomes "&amp;", double spaces collapse).
     * Rows are updated directly so observers and updated_at are left alone, and each
     * change is logged with its previous title.
     */
    public function up(): void
    {
        foreach (['manuscript_records', 'publications'] as $tableName) {
            DB::table($tableName)->select(['id', 'title'])->orderBy('id')->each(function (object $row) use ($tableName): void {
                $title = TitleMarkup::sanitize($row->title);
                if ($title === $row->title) {
                    return;
                }

                DB::table($tableName)->where('id', $row->id)->update(['title' => $title]);

                activity()
                    ->withProperties([
                        'table' => $tableName,
                        'id' => $row->id,
                        'old_title' => $row->title,
                        'new_title' => $title,
                    ])
                    ->log('Normalized title to formatted HTML');
            });
        }
    }
};
