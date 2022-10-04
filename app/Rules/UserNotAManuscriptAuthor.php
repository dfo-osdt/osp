<?php

namespace App\Rules;

use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

/**
 * This rule is used to check if a user is not part of author list of the
 * given manuscript or it's owner. This is used to ensure that the
 * given user can be a reviewer of the manuscript.
 */
class UserNotAManuscriptAuthor implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public ManuscriptRecord $manuscriptRecord)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value    a user id
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /** Gather a list of all user ids that cannot review this manuscript  */
        $emails = $this->manuscriptRecord->manuscriptAuthors()->with('author')->get()->pluck('author.email');
        $invalidUserIds = User::whereIn('email', $emails)->get()->pluck('id');
        $invalidUserIds = $invalidUserIds->push($this->manuscriptRecord->user_id);

        return ! in_array($value, $invalidUserIds->toArray());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute cannot be an author of this manuscript.';
    }
}
