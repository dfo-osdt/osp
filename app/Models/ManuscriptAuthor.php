<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class ManuscriptAuthor extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    // Audit Thresholds
    protected $auditThreshold = 100;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'is_corresponding_author' => 'boolean',
    ];

    // update the manuscript's updated_at timestamp when the author is updated
    protected $touches = ['manuscriptRecord'];

    // Relationships

    /**
     * A manuscript author belongs to a manuscript.
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo('App\Models\ManuscriptRecord');
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
