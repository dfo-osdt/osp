<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PublicationAuthor>
 */
class PublicationAuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'publication_id' => Publication::factory(),
            'author_id' => Author::factory(),
            'organization_id' => Organization::factory(),
            'is_corresponding_author' => false,
            'is_group_author' => false,
        ];
    }

    public function groupAuthor(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_group_author' => true,
        ]);
    }
}
