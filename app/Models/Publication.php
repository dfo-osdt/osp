<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Publication extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    // Audit Thresholds
    protected $auditThreshold = 100;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'is_open_access' => 'boolean',
        'accepted_on' => 'date',
        'published_on' => 'date',
        'embargoed_until' => 'date',
    ];

    public $attributes = [
        // default is false
        'is_open_access' => false,
    ];

    // Relationships
    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo(ManuscriptRecord::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes

    /** Get the open access publications */
    public function scopeOpenAccess($query)
    {
        return $query->where('is_open_access', true);
    }

    /** Get the publication that are no longer under embargo */
    public function scopeNotUnderEmbargo($query)
    {
        return $query->where('embargoed_until', '<', now());
    }

    /** Get the publications under embargo */
    public function scopeUnderEmbargo($query)
    {
        return $query->where('embargoed_until', '>=', now());
    }
}
