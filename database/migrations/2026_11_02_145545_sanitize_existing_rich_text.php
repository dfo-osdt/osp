<?php

use App\Services\RichTextSanitizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Rich text columns now sanitized by the SanitizedRichText cast. */
    private const array COLUMNS = [
        'manuscript_records' => ['abstract', 'pls_en', 'pls_fr', 'relevant_to', 'public_interest_information', 'no_ogl_explanation'],
        'management_review_steps' => ['comments'],
    ];

    /**
     * Sanitize rich text saved before the server enforced it. Rows are updated
     * directly so observers and updated_at are left alone, and each changed field
     * is logged with its previous value.
     */
    public function up(): void
    {
        foreach (self::COLUMNS as $tableName => $columns) {
            DB::table($tableName)->select(['id', ...$columns])->orderBy('id')->each(function (object $row) use ($tableName, $columns): void {
                $changes = [];
                foreach ($columns as $column) {
                    if ($row->{$column} === null) {
                        continue;
                    }
                    $sanitized = RichTextSanitizer::sanitize($row->{$column});
                    if ($sanitized !== $row->{$column}) {
                        $changes[$column] = $sanitized;
                    }
                }

                if ($changes === []) {
                    return;
                }

                DB::table($tableName)->where('id', $row->id)->update($changes);

                activity()
                    ->withProperties([
                        'table' => $tableName,
                        'id' => $row->id,
                        'old' => array_intersect_key((array) $row, $changes),
                    ])
                    ->log('Sanitized rich text');
            });
        }
    }
};
