<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ManuscriptAuthor>
 */
class ManuscriptAuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'manuscript_record_id' => ManuscriptRecord::factory(),
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
