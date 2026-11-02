<?php

namespace App\Casts;

use App\Services\RichTextSanitizer;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Store rich text as HTML limited to the editor's formatting (see RichTextSanitizer).
 */
class SanitizedRichText implements CastsAttributes
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
        return $value === null ? null : RichTextSanitizer::sanitize((string) $value);
    }
}
