<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\HelpfulLinkObserver;
use Database\Factories\HelpfulLinkFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @method static \Database\Factories\HelpfulLinkFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink query()
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $order
 * @property bool $is_visible
 * @property string $title_en
 * @property string $title_fr
 * @property string|null $url_en
 * @property string|null $url_fr
 * @property string $description_en
 * @property string $description_fr
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereDescriptionFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereTitleFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereUrlEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpfulLink whereUrlFr($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(HelpfulLinkObserver::class)]
#[Guarded(['id', 'created_at', 'updated_at'])]
class HelpfulLink extends Model
{
    /** @use HasFactory<HelpfulLinkFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }
}
