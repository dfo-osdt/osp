<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class ManagementReviewStep extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditThreshold = 50;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'user_id',
        'manuscript_record_id',
        'previous_step_id',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'status' => \App\Enums\ManagementReviewStepStatus::class,
        'decision' => \App\Enums\ManagementReviewStepDecision::class,
    ];

    protected $attributes = [
        'comments' => '',
    ];

    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo('App\Models\ManuscriptRecord');
    }

    public function previousStep(): BelongsTo
    {
        return $this->belongsTo('App\Models\ManagementReviewStep', 'previous_step_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
