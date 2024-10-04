<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorEmployment extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorEmploymentFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'orcid_updated_at' => 'timestamp',
        ];
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
