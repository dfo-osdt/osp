<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    /** @use HasFactory<\Database\Factories\AnnouncementFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
	'title_en',
	'title_fr',
	'text_en',
	'text_fr',
	'start_at',
	'end_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    /* protected $hidden = [
       'created_at',
     * ]; */

    /**
     * Return active announcements
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('start_at', '<=', now())
		     ->where('end_at', '>=', now());
    }
}
