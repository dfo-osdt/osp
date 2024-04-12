<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManagementReviewStep extends Model
{
    use HasFactory;

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
        'decision_expected_by' => 'datetime',
        'status' => \App\Enums\ManagementReviewStepStatus::class,
        'decision' => \App\Enums\ManagementReviewStepDecision::class,
    ];

    // Default attributes
    protected $attributes = [
        'comments' => '',
        'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
        'decision' => \App\Enums\ManagementReviewStepDecision::NONE,
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

    public function decisionExpectedBy(): Carbon
    {
        return $this->created_at->addBusinessDays(10);
    }
}
