<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\AuthorEmployment;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuthorEmployment>
 */
class AuthorEmploymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'author_id' => Author::factory()->create()->id,
            'organization_id' => Organization::factory()->create()->id,
            'role_title' => $this->faker->jobTitle(),
            'department_name' => $this->faker->company(),
            'start_date' => $this->faker->date(),
        ];
    }
}
