<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ManuscriptAuthor>
 */
class ManuscriptAuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'manuscript_record_id' => \App\Models\ManuscriptRecord::factory(),
            'author_id' => \App\Models\Author::factory(),
            'organization_id' => \App\Models\Organization::factory(),
            'is_corresponding_author' => false,
        ];
    }
}
