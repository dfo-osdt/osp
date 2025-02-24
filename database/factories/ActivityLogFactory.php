<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Activitylog\Models\Activity;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Spatie\Activitylog\Models\Activity>
 */
class ActivityLogFactory extends Factory
{
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'log_name' => $this->faker->randomElement(['auth', 'librarium', 'user', 'system']),
            'description' => $this->faker->sentence(),
            'subject_type' => User::class,
            'subject_id' => User::factory(),
            'causer_type' => User::class,
            'causer_id' => User::factory(),
            'event' => $this->faker->randomElement(['created', 'updated', 'deleted', 'logged.in']),
            'created_at' => $this->faker->dateTimeBetween('-10 minute', '-1 minute'),
            'updated_at' => $this->faker->dateTimeBetween('now'),
        ];
    }
}
