<?php

namespace App\Casts;

use App\Services\TitleMarkup;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Store a title as canonical formatted HTML (see TitleMarkup).
 * Only normalizes; rejecting invalid markup is ValidTitleMarkup's job.
 */
class SanitizedTitle implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value === null ? null : TitleMarkup::sanitize((string) $value);
    }
}
