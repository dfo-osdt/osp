<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title_en
 * @property string $title_fr
 * @property string $text_en
 * @property string $text_fr
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 *
 * @method static Builder<static>|Announcement active()
 * @method static \Database\Factories\AnnouncementFactory factory($count = null, $state = [])
 * @method static Builder<static>|Announcement newModelQuery()
 * @method static Builder<static>|Announcement newQuery()
 * @method static Builder<static>|Announcement query()
 * @method static Builder<static>|Announcement whereCreatedAt($value)
 * @method static Builder<static>|Announcement whereEndAt($value)
 * @method static Builder<static>|Announcement whereId($value)
 * @method static Builder<static>|Announcement whereStartAt($value)
 * @method static Builder<static>|Announcement whereTextEn($value)
 * @method static Builder<static>|Announcement whereTextFr($value)
 * @method static Builder<static>|Announcement whereTitleEn($value)
 * @method static Builder<static>|Announcement whereTitleFr($value)
 * @method static Builder<static>|Announcement whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
     * Return active announcements
     */
    protected function scopeActive(Builder $query)
    {
        return $query->where('start_at', '<=', now())
            ->where('end_at', '>=', now());
    }
}
