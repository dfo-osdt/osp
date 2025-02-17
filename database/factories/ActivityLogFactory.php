<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

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
            'properties' => [
                'subject_email' => $this->faker->safeEmail(),
                'causer_email' => $this->faker->safeEmail(),
                'ip' => $this->faker->ipv4(),
            ],
            'event' => $this->faker->randomElement(['created', 'updated', 'deleted', 'logged.in']),
            'created_at' => $this->faker->dateTimeBetween('-10 minute', '-1 minute'),
            'updated_at' => $this->faker->dateTimeBetween('now'),
        ];
    }
}
