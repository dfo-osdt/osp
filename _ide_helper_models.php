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
     * @property int|null $user_id
     * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
     * @property-read int|null $audits_count
     * @property-read string $apa_name
     * @property-read string $full_name
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ManuscriptAuthor[] $manuscriptAuthors
     * @property-read int|null $manuscript_authors_count
     * @property-read \App\Models\Organization $organization
     * @property-read \App\Models\User|null $user
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
     * @method static \Illuminate\Database\Eloquent\Builder|Author whereUserId($value)
     */
    class Author extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Invitation
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string $invitation_token
     * @property int $user_id
     * @property int|null $invited_by
     * @property string|null $registered_at
     *
     * @method static \Database\Factories\InvitationFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation query()
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereInvitationToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereInvitedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereRegisteredAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereUserId($value)
     */
    class Invitation extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Journal
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string $title_en
     * @property string|null $title_fr
     * @property string|null $scopus_source_record_id Scopus source record ID
     * @property string $publisher
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Journal dfoSeries()
     * @method static \Database\Factories\JournalFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|Journal newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Journal newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Journal query()
     * @method static \Illuminate\Database\Eloquent\Builder|Journal whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Journal whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Journal wherePublisher($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Journal whereScopusSourceRecordId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Journal whereTitleEn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Journal whereTitleFr($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Journal whereUpdatedAt($value)
     */
    class Journal extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\ManagementReviewStep
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property int $manuscript_record_id
     * @property int|null $previous_step_id
     * @property int $user_id
     * @property \Illuminate\Support\Carbon|null $completed_at
     * @property string|null $comments
     * @property \App\Enums\ManagementReviewStepStatus $status
     * @property \App\Enums\ManagementReviewStepDecision|null $decision
     * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
     * @property-read int|null $audits_count
     * @property-read \App\Models\ManuscriptRecord $manuscriptRecord
     * @property-read ManagementReviewStep|null $previousStep
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\ManagementReviewStepFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep query()
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep whereComments($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep whereCompletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep whereDecision($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep whereManuscriptRecordId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep wherePreviousStepId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManagementReviewStep whereUserId($value)
     */
    class ManagementReviewStep extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable
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
     * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
     * @property-read int|null $audits_count
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
    class ManuscriptAuthor extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable
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
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \App\Enums\ManuscriptRecordType $type primary, secondary, etc.
     * @property \App\Enums\ManuscriptRecordStatus $status draft, submitted, etc.
     * @property string $title
     * @property int $region_id
     * @property int $user_id
     * @property string|null $abstract
     * @property string|null $pls Plain Language Summary
     * @property string|null $scientific_implications
     * @property string|null $regions_and_species
     * @property string|null $relevant_to
     * @property string|null $additional_information
     * @property string|null $sent_for_review_at
     * @property string|null $reviewed_at
     * @property string|null $submitted_to_journal_on
     * @property string|null $accepted_on
     * @property string|null $withdrawn_on
     * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
     * @property-read int|null $audits_count
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ManagementReviewStep[] $managementReviewSteps
     * @property-read int|null $management_review_steps_count
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ManuscriptAuthor[] $manuscriptAuthors
     * @property-read int|null $manuscript_authors_count
     * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
     * @property-read int|null $media_count
     * @property-read \App\Models\Region $region
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\ManuscriptRecordFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord newQuery()
     * @method static \Illuminate\Database\Query\Builder|ManuscriptRecord onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord query()
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereAbstract($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereAcceptedOn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereAdditionalInformation($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptRecord wherePls($value)
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
     * @method static \Illuminate\Database\Query\Builder|ManuscriptRecord withTrashed()
     * @method static \Illuminate\Database\Query\Builder|ManuscriptRecord withoutTrashed()
     */
    class ManuscriptRecord extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \OwenIt\Auditing\Contracts\Auditable
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
     * @property bool $is_validated
     * @property string $name_en
     * @property string $name_fr
     * @property string|null $abbr_en
     * @property string|null $abbr_fr
     * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
     * @property-read int|null $audits_count
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
    class Organization extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Publication
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property string $title
     * @property string|null $doi
     * @property \Illuminate\Support\Carbon|null $accepted_on
     * @property \Illuminate\Support\Carbon|null $published_on
     * @property \Illuminate\Support\Carbon|null $embargoed_until
     * @property bool $is_open_access
     * @property int|null $manuscript_record_id
     * @property int $journal_id
     * @property int $user_id
     * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
     * @property-read int|null $audits_count
     * @property-read \App\Models\Journal $journal
     * @property-read \App\Models\ManuscriptRecord|null $manuscriptRecord
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\PublicationFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Publication newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Publication notUnderEmbargo()
     * @method static \Illuminate\Database\Query\Builder|Publication onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Publication openAccess()
     * @method static \Illuminate\Database\Eloquent\Builder|Publication query()
     * @method static \Illuminate\Database\Eloquent\Builder|Publication underEmbargo()
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereAcceptedOn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereDoi($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereEmbargoedUntil($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereIsOpenAccess($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereJournalId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereManuscriptRecordId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication wherePublishedOn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Publication whereUserId($value)
     * @method static \Illuminate\Database\Query\Builder|Publication withTrashed()
     * @method static \Illuminate\Database\Query\Builder|Publication withoutTrashed()
     */
    class Publication extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable
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
     * @property int $active
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
     * @property-read int|null $audits_count
     * @property-read \App\Models\Author|null $author
     * @property-read string $full_name
     * @property-read \App\Models\Invitation|null $invitation
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
     * @property-read int|null $permissions_count
     * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
     * @property-read int|null $roles_count
     * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
     * @property-read int|null $tokens_count
     *
     * @method static \Database\Factories\UserFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
     * @method static \Illuminate\Database\Eloquent\Builder|User query()
     * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
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
    class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail, \OwenIt\Auditing\Contracts\Auditable
    {
    }
}
