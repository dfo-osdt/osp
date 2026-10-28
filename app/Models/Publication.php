<?php

namespace App\Models;

use App\Contracts\Fundable;
use App\Contracts\Plannable;
use App\Enums\ManuscriptRecordType;
use App\Enums\MediaCollection;
use App\Enums\Permissions\UserPermission;
use App\Enums\PublicationStatus;
use App\Enums\SensitivityLabel;
use App\Enums\SupplementaryFileType;
use App\Traits\FundableTrait;
use App\Traits\PlannableTrait;
use App\Traits\TrimsMediaFileName;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property PublicationStatus $status accepted, published, etc.
 * @property string $title
 * @property string|null $doi
 * @property Carbon|null $accepted_on
 * @property Carbon|null $published_on
 * @property Carbon|null $embargoed_until
 * @property bool $is_open_access
 * @property int|null $manuscript_record_id
 * @property int $journal_id
 * @property int $user_id
 * @property int $region_id
 * @property-read Collection<int, Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read Collection<int, FundingSource> $fundingSources
 * @property-read int|null $funding_sources_count
 * @property-read Journal $journal
 * @property-read ManuscriptRecord|null $manuscriptRecord
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, PublicationAuthor> $publicationAuthors
 * @property-read int|null $publication_authors_count
 * @property-read User $user
 * @property-read Region $region
 *
 * @method static \Database\Factories\PublicationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication forUser(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication notUnderEmbargo()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication openAccess()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication pendingPublish()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication secondaryPublication()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication underEmbargo()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereAcceptedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereEmbargoedUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIsOpenAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereJournalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereManuscriptRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublishedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication withoutTrashed()
 *
 * @property-read PlanningBinderItem|null $planningBinderItem
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereRegionId($value)
 *
 * @property string|null $isbn International Standard Book Number
 * @property string|null $catalogue_number GC Catalogue Number
 * @property string|null $issue_number GC Issue Number
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereCatalogueNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIssueNumber($value)
 * @method static Builder<static>|Publication publishedBetween(string $startDate, string $endDate)
 *
 * @mixin \Eloquent
 */
class Publication extends Model implements Fundable, HasMedia, Plannable
{
    use FundableTrait;
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;
    use PlannableTrait;
    use SoftDeletes;
    use TrimsMediaFileName;

    #[\Override]
    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    #[\Override]
    public $casts = [
        'is_open_access' => 'boolean',
        'accepted_on' => 'date',
        'published_on' => 'date',
        'embargoed_until' => 'date',
        'status' => PublicationStatus::class,
    ];

    #[\Override]
    public $attributes = [
        // default is false
        'is_open_access' => false,
        // default is accepted
        'status' => PublicationStatus::ACCEPTED,
    ];

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relationships
    /**
     * @return BelongsTo<Journal, $this>
     */
    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    /**
     * Manuscript record - can be null as we allow creation of
     * publications without a manuscript record.
     *
     * @return BelongsTo<ManuscriptRecord, $this>
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo(ManuscriptRecord::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The Lead region for which the publication is associated with.
     *
     * @return BelongsTo<Region, $this>
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * @return HasMany<PublicationAuthor, $this>
     */
    public function publicationAuthors(): HasMany
    {
        return $this->hasMany(PublicationAuthor::class);
    }

    // media
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::PUBLICATION->value)
            ->acceptsMimeTypes(['application/pdf']);

        $this->addMediaCollection(MediaCollection::SUPPLEMENTARY_FILE->value)
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    /**
     * Add publication file media model.
     */
    public function addPublicationFile(string|UploadedFile $file, bool $preserveOriginal = false): Media
    {
        $name = $file instanceof UploadedFile ? $file->getClientOriginalName() : basename($file);

        return $this->addMedia($file)
            ->preservingOriginal($preserveOriginal)
            ->usingFileName($this->trimFileName($name))
            ->toMediaCollection(MediaCollection::PUBLICATION->value);
    }

    /**
     * Get publication file media model.
     */
    public function getPublicationFile($uuid)
    {
        return $this->getMedia(MediaCollection::PUBLICATION->value)->where('uuid', $uuid)->first();
    }

    /**
     * Delete publication file media model.
     */
    public function deletePublicationFile($uuid, $force = false): void
    {
        $media = $this->getPublicationFile($uuid);
        if (! $media) {
            throw new FileNotFoundException('File not found.');
        }
        if ($force) {
            $media->delete();

            return;
        }
        if ($media->getCustomProperty('locked', false)) {
            throw new Exception('Cannot delete locked file.');
        }
        $media->delete();
    }

    /**
     * Add supplementary file media model.
     */
    public function addSupplementaryFile(string|UploadedFile $file, SupplementaryFileType $type, ?string $description = null, bool $preserveOriginal = false): Media
    {

        $properties = [
            'supplementary_file_type' => $type->value,
        ];
        if ($description) {
            $properties['description'] = $description;
        }

        if (SupplementaryFileType::isProtectedA($type)) {
            $properties['sensitivity_label'] = SensitivityLabel::ProtectedA->value;
        }

        $name = $file instanceof UploadedFile ? $file->getClientOriginalName() : basename($file);

        return $this->addMedia($file)
            ->preservingOriginal($preserveOriginal)
            ->withCustomProperties($properties)
            ->usingFileName($this->trimFileName($name))
            ->toMediaCollection(MediaCollection::SUPPLEMENTARY_FILE->value);
    }

    /**
     * Get supplementary file media model.
     */
    public function getSupplementaryFile($uuid)
    {
        return $this->getMedia(MediaCollection::SUPPLEMENTARY_FILE->value)->where('uuid', $uuid)->first();
    }

    /**
     * Delete supplementary file media model.
     */
    public function deleteSupplementaryFile($uuid, $force = false): void
    {
        $media = $this->getSupplementaryFile($uuid);
        if (! $media) {
            throw new FileNotFoundException('File not found.');
        }
        if ($force) {
            $media->delete();

            return;
        }
        if ($media->getCustomProperty('locked', false)) {
            throw new Exception('Cannot delete locked file.');
        }
        $media->delete();
    }

    /** Is the publication under embargo? */
    public function isUnderEmbargo(): bool
    {
        if ($this->is_open_access) {
            return false;
        }

        return $this->embargoed_until && $this->embargoed_until->isFuture();
    }

    // Scopes
    /** Get the open access publications
     * @param Builder<static> $query */
    #[Scope]
    protected function openAccess(Builder $query)
    {
        return $query->where('is_open_access', true);
    }

    /** Get the publication that are no longer under embargo
     * @param Builder<static> $query */
    #[Scope]
    protected function notUnderEmbargo(Builder $query)
    {
        return $query->where('embargoed_until', '<', now())->orWhere('is_open_access', true);
    }

    /** Get the publications under embargo
     * @param Builder<static> $query */
    #[Scope]
    protected function underEmbargo(Builder $query)
    {
        return $query->where('embargoed_until', '>=', now());
    }

    /** Secondary publications
     * @param Builder<static> $query */
    #[Scope]
    protected function secondaryPublication(Builder $query)
    {
        return $query->whereIn('journal_id', Journal::query()->dfoSeries()->pluck('id'));
    }

    /** Primary publications are those that are not secondary publications
     * @param Builder<static> $query */
    #[Scope]
    protected function primaryPublication(Builder $query)
    {
        return $query->whereNotIn('journal_id', Journal::query()->dfoSeries()->pluck('id'));
    }

    /** Published date range
     * @param Builder<static> $query */
    #[Scope]
    protected function publishedBetween(Builder $query, string $startDate, string $endDate): void
    {
        $query->whereBetween('published_on', [Date::parse($startDate), Date::parse($endDate)]);
    }

    /**
     * Scope to filter publications that are accepted but not yet marked as published
     * Only includes primary publications that have a manuscript record
     *
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function pendingPublish(Builder $query): void
    {
        $query->where('status', PublicationStatus::ACCEPTED)
            ->whereNotNull('manuscript_record_id')
            ->whereHas('manuscriptRecord', function (\Illuminate\Contracts\Database\Query\Builder $q): void {
                $q->where('type', ManuscriptRecordType::PRIMARY);
            });
    }

    /**
     * Scope publications based on user permissions
     * - Editors with UPDATE_PUBLICATIONS see all publications
     * - Regional editors see all published + unpublished in their region
     * - Regional observers see all published + unpublished in their region
     * - Regular users see only published publications
     *
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function forUser(Builder $query, User $user): void
    {
        // if user has permission to update all publications, show everything
        if ($user->hasPermissionTo(UserPermission::UPDATE_PUBLICATIONS)) {
            return;
        }

        // if user is a regional editor or observer, show published + unpublished in their region
        if ($user->hasRegionalRole()) {
            // Get all regional permissions (both view and edit)
            $permissions = collect(array_merge(
                UserPermission::getRegionalEditorPubEditPermissions(),
                UserPermission::getRegionalViewPermissions()
            ))->pluck('value');

            $userPermissions = $user->getAllPermissions()->pluck('name');
            $slugs = $permissions->intersect($userPermissions)
                ->map(fn ($perm): ?string => explode('_', (string) $perm)[2] ?? null)
                ->filter()
                ->unique()
                ->all();

            $query->where('status', PublicationStatus::PUBLISHED)
                ->orWhere(function (\Illuminate\Contracts\Database\Query\Builder $q) use ($slugs): void {
                    $q->where('status', PublicationStatus::ACCEPTED)
                        ->whereHas('region', function (\Illuminate\Contracts\Database\Query\Builder $regionQuery) use ($slugs): void {
                            $regionQuery->whereIn('slug', $slugs);
                        });
                });

            return;
        }

        // regular users only see published publications
        $query->where('status', PublicationStatus::PUBLISHED);
    }

    /**
     * Generate a RIS citation string for this publication.
     * Requires journal and publicationAuthors.author to be loaded.
     */
    public function toRis(): string
    {
        $isDfo = $this->journal->isDfoSeries();

        $lines = [];
        $lines[] = 'TY  - '.($isDfo ? 'RPRT' : 'JOUR');
        $lines[] = 'TI  - '.$this->title;

        foreach ($this->publicationAuthors ?? [] as $pubAuthor) {
            $authorName = $pubAuthor->is_group_author
                ? $pubAuthor->organization->name_en
                : $pubAuthor->author->last_name.', '.$pubAuthor->author->first_name;
            $lines[] = 'AU  - '.$authorName;
            $affiliation = $pubAuthor->organization->name_en;
            if ($pubAuthor->organization->ror_identifier) {
                $affiliation .= ' ('.$pubAuthor->organization->ror_identifier.')';
            }
            $lines[] = 'AD  - '.$affiliation;
        }

        if ($this->published_on) {
            $lines[] = 'PY  - '.$this->published_on->format('Y');
            $lines[] = 'DA  - '.$this->published_on->format('Y/m/d');
        }

        $lines[] = 'JO  - '.$this->journal->title;
        $lines[] = 'PB  - '.$this->journal->publisher;
        if ($this->journal->issn) {
            $lines[] = 'SN  - '.trim((string) $this->journal->issn);
        }
        if ($this->doi) {
            $lines[] = 'DO  - '.str_replace('https://doi.org/', '', $this->doi);
        }
        if ($this->catalogue_number) {
            $lines[] = 'SN  - '.$this->catalogue_number;
        }
        if ($this->isbn) {
            $lines[] = 'SN  - '.$this->isbn;
        }

        $lines[] = 'ER  - ';

        return implode("\r\n", $lines)."\r\n";
    }

    /**
     * Generate a BibTeX entry for this publication.
     * Requires journal and publicationAuthors.author/organization to be loaded.
     */
    public function toBibtex(): string
    {
        $isDfo = $this->journal->isDfoSeries();

        $fields = [
            'author' => $this->publicationAuthors
                ->map($this->bibtexAuthor(...))
                ->implode(' and '),
            'title' => $this->bibtexTitle($this->title),
        ];

        if ($isDfo) {
            $fields['type'] = $this->escapeBibtex($this->journal->title);
            $fields['institution'] = $this->escapeBibtex($this->journal->publisher);
            $fields['number'] = $this->escapeBibtex($this->issue_number);
        } else {
            $fields['journal'] = $this->escapeBibtex($this->journal->title);
            $fields['publisher'] = $this->escapeBibtex($this->journal->publisher);
        }

        $fields['year'] = $this->published_on?->format('Y');
        $fields['month'] = $this->published_on ? strtolower($this->published_on->format('M')) : null;
        $fields['issn'] = $this->escapeBibtex(trim((string) $this->journal->issn));
        $fields['isbn'] = $this->escapeBibtex($this->isbn);
        // DOI is a verbatim field in biblatex, so it is never escaped
        $fields['doi'] = $this->doi ? str_replace('https://doi.org/', '', $this->doi) : null;

        $lines = collect($fields)
            ->filter(fn (?string $value): bool => filled($value))
            ->map(fn (string $value, string $name): string => "  {$name} = ".($name === 'month' ? $value : '{'.$value.'}'))
            ->implode(",\n");

        return '@'.($isDfo ? 'techreport' : 'article').'{'.$this->bibtexKey().",\n".$lines."\n}\n";
    }

    /**
     * Citation key: first author's name, year and id (e.g. Cote2024_123).
     * Group authors use their organization's name.
     */
    private function bibtexKey(): string
    {
        $firstAuthor = $this->publicationAuthors->first();
        $authorName = $firstAuthor?->is_group_author
            ? $firstAuthor->organization->name_en
            : $firstAuthor?->author->last_name;
        $name = preg_replace('/[^A-Za-z0-9]/', '', Str::ascii((string) $authorName));

        return ($name ?: 'publication').$this->published_on?->format('Y').'_'.$this->id;
    }

    private function bibtexAuthor(PublicationAuthor $publicationAuthor): string
    {
        if ($publicationAuthor->is_group_author) {
            return '{'.$this->escapeBibtex($publicationAuthor->organization->name_en).'}';
        }

        return $this->escapeBibtex($publicationAuthor->author->last_name.', '.$publicationAuthor->author->first_name);
    }

    /**
     * Escape the title and brace-protect words with an inner capital (acronyms like DFO or eDNA)
     * so citation styles that change case leave them intact.
     */
    private function bibtexTitle(string $title): string
    {
        return preg_replace_callback('/\S+/u', function (array $match): string {
            $word = $this->escapeBibtex($match[0]);

            return preg_match('/\p{L}.*\p{Lu}/u', $match[0]) ? '{'.$word.'}' : $word;
        }, $title);
    }

    private function escapeBibtex(?string $value): string
    {
        return strtr((string) $value, [
            '\\' => '\textbackslash{}',
            '{' => '\{',
            '}' => '\}',
            '&' => '\&',
            '%' => '\%',
            '$' => '\$',
            '#' => '\#',
            '_' => '\_',
            '~' => '\textasciitilde{}',
            '^' => '\textasciicircum{}',
        ]);
    }
}
