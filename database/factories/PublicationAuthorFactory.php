<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PublicationAuthor>
 */
class PublicationAuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'publication_id' => \App\Models\Publication::factory(),
            'author_id' => \App\Models\Author::factory(),
            'organization_id' => \App\Models\Organization::factory(),
            'is_corresponding_author' => false,
        ];
    }
}
