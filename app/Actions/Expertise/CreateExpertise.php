<?php

namespace App\Actions\Expertise;

use App\Models\Expertise;
use App\Models\User;

class CreateExpertise
{
    /**
     * Create a new user-submitted expertise record.
     *
     * @param  array{name_en: string, name_fr: string}  $data
     */
    public static function handle(array $data, User $user): Expertise
    {
        $expertise = Expertise::query()->create([
            'name_en' => $data['name_en'],
            'name_fr' => $data['name_fr'],
            'is_validated' => false,
        ]);

        activity()
            ->performedOn($expertise)
            ->causedBy($user)
            ->withProperties([
                'name_en' => $expertise->name_en,
                'name_fr' => $expertise->name_fr,
            ])
            ->log('expertise.created');

        return $expertise;
    }
}
