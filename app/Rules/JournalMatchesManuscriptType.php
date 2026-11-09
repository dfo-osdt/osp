<?php

namespace App\Rules;

use App\Enums\ManuscriptRecordType;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * A publication is in a DFO series if and only if it comes from a secondary
 * manuscript record. Without a manuscript record (manual entry), or with a
 * primary or preprint one, the journal must not be a DFO series.
 */
class JournalMatchesManuscriptType implements ValidationRule
{
    public function __construct(public ?ManuscriptRecord $manuscriptRecord) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $journal = Journal::query()->find($value);
        if ($journal === null) {
            return;
        }

        $mustBeDfoSeries = $this->manuscriptRecord?->type === ManuscriptRecordType::SECONDARY;

        if ($mustBeDfoSeries && ! $journal->isDfoSeries()) {
            $fail('validation.journal_must_be_dfo_series')->translate();
        } elseif (! $mustBeDfoSeries && $journal->isDfoSeries()) {
            $fail($this->manuscriptRecord === null
                ? 'validation.journal_dfo_series_needs_manuscript_record'
                : 'validation.journal_cannot_be_dfo_series')->translate();
        }
    }
}
