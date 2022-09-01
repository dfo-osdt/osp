<?php

namespace App\Models;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


    // Relationships

    /**
     * A manuscript has a lead region.
     */
    public function region(){
        return $this->belongsTo('App\Models\Region');
    }

}
