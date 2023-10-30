<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUlid
{
    /**
     * Boot the trait.
     */
    protected static function bootHasUlid(): void
    {
        static::creating(function (Model $model) {
            $model->ulid = Str::ulid();
        });
    }

    public static function findByUlid(string $ulid): ?Model
    {
        return static::where('ulid', $ulid)->first();
    }
}
