<?php

namespace App\Models;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManuscriptRecord extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
        'user_id',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'type' => ManuscriptRecordType::class,
        'status' => ManuscriptRecordStatus::class,
    ];

    // default values for optional fields
    protected $attributes = [
        'abstract' => '',
        'pls_en' => '',
        'pls_fr' => '',
        'scientific_implications' => '',
        'regions_and_species' => '',
        'relevant_to' => '',
        'additional_information' => '',
    ];

    // Relationships

    /**
     * A manuscript has a lead region.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo('App\Models\Region');
    }

    /**
     * A manuscripts has several ManuscriptAuthors
     */
    public function manuscriptAuthors(): HasMany
    {
        return $this->hasMany('App\Models\ManuscriptAuthor');
    }
}
