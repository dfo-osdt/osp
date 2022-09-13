<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relationships

    /** Authors */
    public function authors(): HasMany
    {
        return $this->hasMany('App\Models\Author');
    }

    /** ManuscriptAuthors */
    public function manuscriptAuthors(): HasMany
    {
        return $this->hasMany('App\Models\ManuscriptAuthor');
    }
}
