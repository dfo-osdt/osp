<?php

namespace App\Rules;

use App\Models\ManuscriptRecord;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * This rule is used to check if a user is not part of author list of the
 * given manuscript or it's owner. This is used to ensure that the
 * given user can be a reviewer of the manuscript.
 */
class UserNotAManuscriptAuthor implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public ManuscriptRecord $manuscriptRecord) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** Gather a list of all user ids that cannot review this manuscript  */
        $emails = $this->manuscriptRecord->manuscriptAuthors()->with('author')->get()->pluck('author.email');
        $invalidUserIds = \App\Models\User::query()->whereIn('email', $emails)->pluck('id');
        $invalidUserIds = $invalidUserIds->push($this->manuscriptRecord->user_id);

        if (in_array($value, $invalidUserIds->toArray())) {
            $fail('The :attribute cannot be an author or owner of this manuscript.');
        }
    }
}
