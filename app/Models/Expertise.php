<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Expertise extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // recursive relationship params
    public function getParentKeyName()
    {
        return 'parent_tid';
    }

    public function getLocalKeyName()
    {
        return 'tid';
    }

    // non-recursive relationships

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function recursiveAuthors()
    {
        return $this->belongsToManyOfDescendantsAndSelf(Author::class);
    }
}
