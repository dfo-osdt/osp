<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PublicationAuthor extends Model
{
    use HasFactory;
    use LogsActivity;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'is_corresponding_author' => 'boolean',
    ];

    // update the publication's updated_at timestamp when the author is updated
    protected $touches = ['publication'];

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relationships

    /**
     * A publication author belongs to a publication.
     */
    public function publication(): BelongsTo
    {
        return $this->belongsTo('App\Models\Publication');
    }

    /** Organization */
    public function organization(): BelongsTo
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /** Author */
    public function author(): BelongsTo
    {
        return $this->belongsTo('App\Models\Author');
    }
}
