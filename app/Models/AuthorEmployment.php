<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AuthorEmployment extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorEmploymentFactory> */
    use HasFactory;
    use LogsActivity;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
        'orcid_putcode',
        'orcid_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'orcid_updated_at' => 'timestamp',
        ];
    }

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function isSyncedWithOrcid(): bool
    {
        return $this->orcid_putcode !== null;
    }

    public function needsSyncWithOrcid(): bool
    {
        return $this->orcid_updated_at > $this->updated_at || $this->orcid_putcode === null;
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Note that we allow a different organization here instead
     * of the one in the author's profile as the author may
     * have had been employed by a different organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
