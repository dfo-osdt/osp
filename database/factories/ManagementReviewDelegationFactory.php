<?php

namespace Database\Factories;

use App\Models\ManagementReviewDelegation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ManagementReviewDelegation>
 */
class ManagementReviewDelegationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'delegate_user_id' => User::factory(),
            'starts_at' => null,
            'ends_at' => now()->addDays(30),
            'ended_early_at' => null,
            'comment' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state([
            'starts_at' => now()->subDays(30),
            'ends_at' => now()->subDay(),
        ]);
    }

    public function future(): static
    {
        return $this->state([
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(37),
        ]);
    }

    public function endedEarly(): static
    {
        return $this->state([
            'ended_early_at' => now()->subDay(),
        ]);
    }
}
