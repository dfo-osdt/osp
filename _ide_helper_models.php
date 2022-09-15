<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */

namespace App\Models{
    /**
     * App\Models\Author
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string $first_name
     * @property string $last_name
     * @property string|null $orcid
     * @property string $email
     * @property int $organization_id
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ManuscriptAuthor[] $manuscriptAuthors
     * @property-read int|null $manuscript_authors_count
     * @property-read \App\Models\Organization $organization
     *
     * @method static \Database\Factories\AuthorFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|Author newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Author newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Author query()
     * @method static \Illuminate\Database\Eloquent\Builder|Author whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Author whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Author whereFirstName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Author whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Author whereLastName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Author whereOrcid($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Author whereOrganizationId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Author whereUpdatedAt($value)
     */
    class Author extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\ManuscriptAuthor
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property int $manuscript_record_id
     * @property int $author_id
     * @property int $organization_id
     * @property bool $is_corresponding_author
     * @property-read \App\Models\Author $author
     * @property-read \App\Models\ManuscriptRecord $manuscriptRecord
     * @property-read \App\Models\Organization $organization
     *
     * @method static \Database\Factories\ManuscriptAuthorFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor query()
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereAuthorId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereIsCorrespondingAuthor($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereManuscriptRecordId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereOrganizationId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereUpdatedAt($value)
     */
    class ManuscriptAuthor extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\ManuscriptRecord
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \App\Enums\ManuscriptRecordType $type primary, secondary, etc.
     * @property \App\Enums\ManuscriptRecordStatus $status draft, submitted, etc.
     * @property string $title
     * @property int $region_id
     * @property int $user_id
     * @property string|null $abstract
     * @property string|null $pls_en Plain Language Summary (English)
     * @property string|null $pls_fr Plain Language Summary (French)
     * @property string|null $scientific_implications
     * @property string|null $regions_and_species
     * @property string|null $relevant_to
     * @property string|null $additional_information
     * @property string|null $sent_for_review_at
     * @property string|null $reviewed_at
     * @property string|null $submitted_to_journal_on
     * @property string|null $accepted_on
     * @property string|null $withdrawn_on
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ManuscriptAuthor[] $manuscriptAuthors
     * @property-read int|null $manuscript_authors_count
     * @property-read \App\Models\Region $region
     *
     * @method static \Database\Factories\ManuscriptRecordFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord query()
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereAbstract($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereAcceptedOn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereAdditionalInformation($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord wherePlsEn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord wherePlsFr($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereRegionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereRegionsAndSpecies($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereRelevantTo($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereReviewedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereScientificImplications($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereSentForReviewAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereSubmittedToJournalOn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereWithdrawnOn($value)
     */
    class ManuscriptRecord extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Organization
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property int $is_validated
     * @property string $name_en
     * @property string $name_fr
     * @property string|null $abbr_en
     * @property string|null $abbr_fr
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Author[] $authors
     * @property-read int|null $authors_count
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ManuscriptAuthor[] $manuscriptAuthors
     * @property-read int|null $manuscript_authors_count
     *
     * @method static \Database\Factories\OrganizationFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|Organization newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Organization newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Organization query()
     * @method static \Illuminate\Database\Eloquent\Builder|Organization whereAbbrEn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Organization whereAbbrFr($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Organization whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Organization whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Organization whereIsValidated($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Organization whereNameEn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Organization whereNameFr($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Organization whereUpdatedAt($value)
     */
    class Organization extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Region
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string $name_en
     * @property string $name_fr
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Region query()
     * @method static \Illuminate\Database\Eloquent\Builder|Region whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Region whereNameEn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Region whereNameFr($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Region whereUpdatedAt($value)
     */
    class Region extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\User
     *
     * @property int $id
     * @property string $first_name
     * @property string $last_name
     * @property string $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
     * @property-read int|null $tokens_count
     *
     * @method static \Database\Factories\UserFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User query()
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
     */
    class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail
    {
    }
}
